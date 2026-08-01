<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>E-Posta Doğrulama</title>

<link rel="stylesheet" href="assets/css/style.css">
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