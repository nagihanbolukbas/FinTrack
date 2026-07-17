<?php
require_once "config/database.php";
session_start();

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$message = "";
$redirect = false;

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $email = trim($_POST["email"]);

    $query = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $query->execute([$email]);

    if($query->rowCount()==0){

        $message = "Hesabınız bulunmamaktadır. Kayıt sayfasına yönlendiriliyorsunuz...";
        $redirect = true;

    } else {

        $resetCode = rand(100000,999999);

        $resetExpire = date(
            "Y-m-d H:i:s",
            strtotime("+10 minutes")
        );

        $update = $pdo->prepare("
            UPDATE users
            SET reset_code=?,
                reset_expire=?
            WHERE email=?
        ");

        $update->execute([
            $resetCode,
            $resetExpire,
            $email
        ]);

        try{

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;

            $mail->Username = "fintrackproject101@gmail.com";
            $mail->Password = "x q g m t g p g o r j k l q m n";

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
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

        }catch(Exception $e){

            $message = "Mail gönderilemedi: ".$mail->ErrorInfo;

        }

    }

}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Şifremi Unuttum</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="auth-page">
<div class="auth-container">

<div class="auth-right">

<h2>Şifremi Unuttum</h2>

<?php if($message!=""){ ?>
<div class="success">
<?= $message ?>
</div>
<?php } ?>

<form method="POST">

<div class="input-group">
<input
type="email"
name="email"
placeholder="E-Posta Adresiniz"
required>
</div>

<button class="auth-btn">
Devam Et
</button>

</form>

<div class="auth-footer">
<a href="login.php">Giriş sayfasına dön</a>
</div>

</div>

</div>
</div>

<?php if($redirect){ ?>

<script>
setTimeout(function(){
    window.location.href="register.php";
},3000);
</script>

<?php } ?>

</body>
</html>