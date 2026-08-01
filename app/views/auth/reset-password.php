<!DOCTYPE html>
<html lang="tr">
<head>

<meta charset="UTF-8">

<title>Yeni Şifre | FinTrack</title>

<link rel="stylesheet" href="assets/css/style.css">

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