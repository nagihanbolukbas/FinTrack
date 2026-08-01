<?php

require_once "app/models/User.php";

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // LOGIN
    public function login()
    {
        $error = "";
        $redirect = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            if (empty($email) || empty($password)) {

                $error = "Lütfen tüm alanları doldurun.";

            } else {

                $userModel = new User($this->pdo);

                $user = $userModel->findByEmail($email);

                if (!$user) {

                    $error = "Bu e-posta adresiyle kayıtlı bir hesap bulunmamaktadır. 3 saniye içinde kayıt sayfasına yönlendirileceksiniz.";
                    $redirect = true;

                } else {

                    if (password_verify($password, $user["password"])) {

                        if ($user["is_verified"] == 0) {

                            $error = "Lütfen önce e-posta adresinizi doğrulayın.";

                        } else {

                            $_SESSION["id"] = $user["id"];
                            $_SESSION["first_name"] = $user["first_name"];
                            $_SESSION["last_name"] = $user["last_name"];
                            $_SESSION["email"] = $user["email"];

                            header("Location: dashboard.php");
                            exit;
                        }

                    } else {

                        $error = "E-posta veya şifre hatalı.";

                    }

                }

            }

        }

        require "app/views/auth/login.php";
    }

    // REGISTER
    public function register()
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $first_name = trim($_POST["first_name"]);
            $last_name  = trim($_POST["last_name"]);
            $email      = trim($_POST["email"]);
            $password   = $_POST["password"];

            if (
                empty($first_name) ||
                empty($last_name) ||
                empty($email) ||
                empty($password)
            ) {

                $error = "Lütfen tüm alanları doldurun.";

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = "Geçerli bir e-posta adresi giriniz.";

            } elseif (strlen($password) < 6) {

                $error = "Şifre en az 6 karakter olmalıdır.";

            } else {

                $userModel = new User($this->pdo);

                if ($userModel->emailExists($email)) {

                    $error = "Bu e-posta adresi zaten kayıtlı.";

                } else {

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $verificationCode = rand(100000,999999);

                    $verificationExpiry = date(
                        "Y-m-d H:i:s",
                        strtotime("+10 minutes")
                    );

                    $userModel->createUser(
                        $first_name,
                        $last_name,
                        $email,
                        $passwordHash,
                        $verificationCode,
                        $verificationExpiry
                    );

                    try {

                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPAuth = true;

                        $mail->Username = "fintrackproject101@gmail.com";
                        $mail->Password = "x q g m t g p g o r j k l q m n";

                        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->CharSet = "UTF-8";

                        $mail->setFrom(
                            "mailadresiniz@gmail.com",
                            "FinTrack"
                        );

                        $mail->addAddress($email);

                        $mail->isHTML(true);

                        $mail->Subject = "FinTrack E-Posta Doğrulama";

                        $mail->Body = "
                        <h2>FinTrack</h2>

                        <p>Merhaba <b>$first_name</b>,</p>

                        <p>Doğrulama kodunuz:</p>

                        <h1 style='color:#2563EB'>
                            $verificationCode
                        </h1>

                        <p>Bu kod 10 dakika geçerlidir.</p>
                        ";

                        $mail->send();

                        $_SESSION["verify_email"] = $email;

                        header("Location: verify.php");
                        exit;

                    } catch (Exception $e) {

                        $error = "E-posta gönderilemedi: " . $mail->ErrorInfo;

                    }

                }

            }

        }

        require "app/views/auth/register.php";
    
}
public function verify()
{
    $error = "";
    $success = "";

    if (!isset($_SESSION["verify_email"])) {
        header("Location: login.php");
        exit;
    }

    $email = $_SESSION["verify_email"];

    if (isset($_POST["verify"])) {

        $code = trim($_POST["code"]);

        $userModel = new User($this->pdo);

        $user = $userModel->getByEmail($email);

        if (!$user) {

            $error = "Kullanıcı bulunamadı.";

        } elseif ($user["verification_code"] != $code) {

            $error = "Doğrulama kodu hatalı.";

        } elseif (strtotime($user["verification_expiry"]) < time()) {

            $error = "Kodun süresi doldu.";

        } else {

            $userModel->verifyUser($user["id"]);

            unset($_SESSION["verify_email"]);

            $_SESSION["success"] = "E-posta doğrulandı. Giriş yapabilirsiniz.";

            header("Location: login.php");
            exit;
        }
    }

    require "app/views/auth/verify.php";
}
public function verifyReset()
{
    if (!isset($_SESSION["reset_email"])) {
        header("Location: forgot-password.php");
        exit;
    }

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $code = trim($_POST["code"]);
        $email = $_SESSION["reset_email"];

        $userModel = new User($this->pdo);

        $user = $userModel->verifyResetCode($email, $code);

        if ($user) {

            $_SESSION["reset_verified"] = true;

            header("Location: reset-password.php");
            exit;

        } else {

            $message = "Kod hatalı veya süresi dolmuş.";

        }
    }

    require "app/views/auth/verify-reset.php";
} 
/*forgot-pass*/
public function forgotPassword()
{
    date_default_timezone_set("Europe/Istanbul");

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    $message = "";
    $redirect = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = trim($_POST["email"]);

        $userModel = new User($this->pdo);

        if (!$userModel->emailExistsForReset($email)) {

            $message = "Hesabınız bulunmamaktadır. Kayıt sayfasına yönlendiriliyorsunuz...";
            $redirect = true;

        } else {

            $resetCode = rand(100000,999999);

            $resetExpire = date(
                "Y-m-d H:i:s",
                strtotime("+10 minutes")
            );

            $userModel->saveResetCode(
                $email,
                $resetCode,
                $resetExpire
            );

            try{

                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;

                $mail->Username = "fintrackproject101@gmail.com";
                $mail->Password = "x q g m t g p g o r j k l q m n";

                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = "UTF-8";

                $mail->setFrom(
                    "fintrackproject101@gmail.com",
                    "FinTrack"
                );

                $mail->addAddress($email);

                $mail->isHTML(true);

                $mail->Subject = "FinTrack Şifre Sıfırlama";

                $mail->Body = "
                <h2>FinTrack</h2>

                <p>Şifre sıfırlama kodunuz:</p>

                <h1 style='color:#2563EB'>
                $resetCode
                </h1>

                <p>Bu kod 10 dakika geçerlidir.</p>
                ";

                $mail->send();

                $_SESSION["reset_email"] = $email;

                header("Location: verify-reset.php");
                exit;

            } catch (Exception $e) {

                $message = "Mail gönderilemedi: ".$mail->ErrorInfo;

            }

        }

    }

    require "app/views/auth/forgot-password.php";
}
public function resetPassword()
{
    if (
        !isset($_SESSION["reset_verified"]) ||
        !isset($_SESSION["reset_email"])
    ) {
        header("Location: forgot-password.php");
        exit;
    }

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $password  = $_POST["password"];
        $password2 = $_POST["password2"];

        if (empty($password) || empty($password2)) {

            $message = "Lütfen tüm alanları doldurun.";

        } elseif (strlen($password) < 6) {

            $message = "Şifre en az 6 karakter olmalıdır.";

        } elseif ($password != $password2) {

            $message = "Şifreler eşleşmiyor.";

        } else {

            $userModel = new User($this->pdo);

            $currentUser = $userModel->getPasswordByEmail($_SESSION["reset_email"]);

            if (password_verify($password, $currentUser["password"])) {

                $message = "Yeni şifreniz eski şifrenizle aynı olamaz.";

            } else {

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $userModel->updatePassword(
                    $_SESSION["reset_email"],
                    $hash
                );

                unset($_SESSION["reset_verified"]);
                unset($_SESSION["reset_email"]);

                $_SESSION["success"] = "Şifreniz başarıyla güncellendi.";

                header("Refresh:3; url=login.php");

                $message = "Şifreniz başarıyla değiştirildi. 3 saniye sonra giriş sayfasına yönlendirileceksiniz.";
            }
        }
    }

    require "app/views/auth/reset-password.php";
}
}