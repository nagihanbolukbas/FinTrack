<?php
session_start();
require_once "config/database.php";

if(!isset($_SESSION["reset_email"])){
    header("Location: forgot-password.php");
    exit;
}

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $code = trim($_POST["code"]);
    $email = $_SESSION["reset_email"];

   $query = $pdo->prepare("
    SELECT id
    FROM users
    WHERE email=?
    AND reset_code=?
    AND reset_expire > NOW()
");

    $query->execute([$email,$code]);

    if($query->rowCount()>0){

        $_SESSION["reset_verified"] = true;

        header("Location: reset-password.php");
        exit;

    }else{

        $message = "Kod hatalı veya süresi dolmuş.";

    }

}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kodu Doğrula | FinTrack</title>

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="auth-page">
<div class="auth-container">

<div class="auth-right">

<h2>Doğrulama Kodu</h2>

<?php if($message!=""){ ?>
<div class="error">
<?= $message ?>
</div>
<?php } ?>

<form method="POST">

<div class="input-group">
<input
type="text"
name="code"
maxlength="6"
placeholder="6 Haneli Kod"
required>
</div>

<button class="auth-btn">
Doğrula
</button>

</form>

</div>

</div>
</div>

</body>
</html>