<?php
session_start();
require_once "config/database.php";

$error = "";
$success = "";

if(!isset($_SESSION["verify_email"])){
    header("Location: login.php");
    exit;
}

$email = $_SESSION["verify_email"];

if(isset($_POST["verify"])){

    $code = trim($_POST["code"]);

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email=?
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){

        $error = "Kullanıcı bulunamadı.";

    }elseif($user["verification_code"] != $code){

        $error = "Doğrulama kodu hatalı.";

    }elseif(strtotime($user["verification_expiry"]) < time()){

        $error = "Kodun süresi doldu.";

    }else{

        $update = $pdo->prepare("
            UPDATE users
            SET is_verified=1,
                verification_code=NULL,
                verification_expiry=NULL
            WHERE id=?
        ");

        $update->execute([$user["id"]]);

        unset($_SESSION["verify_email"]);

        $_SESSION["success"]="E-posta doğrulandı. Giriş yapabilirsiniz.";

        header("Location: login.php");
        exit;
    }

}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>E-Posta Doğrulama</title>

<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="auth-page">

<div class="auth-container">

<div class="auth-right">

<h2>E-Posta Doğrulama</h2>

<p>Mail adresinize gönderilen 6 haneli kodu giriniz.</p>

<?php if($error!=""){ ?>
<div class="error"><?= $error ?></div>
<?php } ?>

<form method="POST">

<div class="input-group">
<input
type="text"
name="code"
maxlength="6"
placeholder="6 Haneli Kod..."
required>
</div>

<button
class="auth-btn"
name="verify">

Doğrula

</button>

</form>

</div>

</div>

</div>

</body>
</html>