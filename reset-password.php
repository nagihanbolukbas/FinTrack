<?php
session_start();
require_once "config/database.php";

if(
    !isset($_SESSION["reset_verified"]) ||
    !isset($_SESSION["reset_email"])
){
    header("Location: forgot-password.php");
    exit;
}

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $password  = $_POST["password"];
    $password2 = $_POST["password2"];

    if(empty($password) || empty($password2)){

        $message = "Lütfen tüm alanları doldurun.";

    }elseif(strlen($password) < 6){

        $message = "Şifre en az 6 karakter olmalıdır.";

    }elseif($password != $password2){

        $message = "Şifreler eşleşmiyor.";

    }else{

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE users
            SET password=?,
                reset_code=NULL,
                reset_expire=NULL
            WHERE email=?
        ");

        $update->execute([
            $hash,
            $_SESSION["reset_email"]
        ]);

        unset($_SESSION["reset_verified"]);
        unset($_SESSION["reset_email"]);

        $_SESSION["success"] = "Şifreniz başarıyla güncellendi.";

        header("Refresh:3; url=login.php");

        $message = "Şifreniz başarıyla değiştirildi. 3 saniye sonra giriş sayfasına yönlendirileceksiniz.";
    }

}
?>

<!DOCTYPE html>
<html lang="tr">
<head>

<meta charset="UTF-8">

<title>Yeni Şifre | FinTrack</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="auth-page">

<div class="auth-container">

<div class="auth-right">

<h2>Yeni Şifre Oluştur</h2>

<?php if($message!=""){ ?>

<div class="success">
<?= $message ?>
</div>

<?php } ?>

<form method="POST">

<div class="input-group">

<input
type="password"
name="password"
placeholder="Yeni Şifre"
required>

</div>

<div class="input-group">

<input
type="password"
name="password2"
placeholder="Yeni Şifre Tekrar"
required>

</div>

<button class="auth-btn">

Şifreyi Güncelle

</button>

</form>

</div>

</div>

</div>

</body>
</html>