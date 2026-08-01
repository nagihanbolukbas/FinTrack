<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Şifremi Unuttum</title>
<link rel="stylesheet" href="assets/css/style.css">
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