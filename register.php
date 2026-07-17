<?php
session_start();

require_once "config/database.php";

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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

        $query = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $query->execute([$email]);

        if ($query->rowCount() > 0) {

            $error = "Bu e-posta adresi zaten kayıtlı.";

        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $verificationCode = rand(100000,999999);

            $verificationExpiry = date(
                "Y-m-d H:i:s",
                strtotime("+10 minutes")
            );

            $insert = $pdo->prepare("
                INSERT INTO users
                (
                    first_name,
                    last_name,
                    email,
                    password,
                    verification_code,
                    verification_expiry,
                    is_verified
                )
                VALUES (?,?,?,?,?,?,0)
            ");

            $insert->execute([
                $first_name,
                $last_name,
                $email,
                $passwordHash,
                $verificationCode,
                $verificationExpiry
            ]);

            try{

                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;

                // KENDİ GMAIL ADRESİNİ YAZ
                $mail->Username = "fintrackproject101@gmail.com";

                // GOOGLE APP PASSWORD
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

            }catch(Exception $e){

                $error = "E-posta gönderilemedi: ".$mail->ErrorInfo;

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kayıt Ol | FinTrack</title>

<link rel="stylesheet" href="css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>


<div class="auth-page">

    <div class="auth-container">

        <div class="auth-left">

            <h1>FinTrack</h1>

            <p>

                Finansal geleceğini bugünden planla.

            </p>

            <ul>

                <li>✔ Gelir Takibi</li>

                <li>✔ Gider Yönetimi</li>

                <li>✔ Finansal Raporlar</li>

                <li>✔ Tasarruf Hedefleri</li>

            </ul>

        </div>

        <div class="auth-right">

            <h2>Kayıt Ol</h2>

            <?php if($error!=""){ ?>

                <div class="error">

                    <?= $error ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="input-group">

                    <input
                        type="text"
                        name="first_name"
                        placeholder="Ad"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Soyad"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="email"
                        name="email"
                        placeholder="E-Posta Adresi"
                        required>

                </div>

             <div class="input-group">

    <input
        type="password"
        name="password"
        placeholder="Şifre"
        required>

</div>

<small class="password-info">
    Şifre en az <strong>6 karakter</strong> olmalıdır.
</small>

             

                <button class="auth-btn">

                    Hesap Oluştur

                </button>

            </form>

            <div class="auth-footer">

                Zaten hesabın var mı?

                <a href="login.php">

                    Giriş Yap

                </a>

            </div>

        </div>

    </div>

</div>
</body>


</html>