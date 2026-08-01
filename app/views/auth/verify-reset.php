<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kodu Doğrula | FinTrack</title>

<link rel="stylesheet" href="assets/css/style.css">
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
placeholder="6 Haneli Kod..."
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