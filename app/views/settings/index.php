<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Ayarlar | FinTrack</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<div class="settings-header">

    <div>
        <h2>⚙️ Hesap Ayarları</h2>
        <p>FinTrack hesabınızı ve uygulama tercihlerinizi yönetin.</p>
    </div>

</div>


<?php if(!empty($message)){ ?>

<p class="success">
<?= htmlspecialchars($message) ?>
</p>

<?php } ?>


<?php if(!empty($error)){ ?>

<p class="error">
<?= htmlspecialchars($error) ?>
</p>

<?php } ?>


<!-- Profil -->

<div class="settings-card">

<h3>

<i class="fa-solid fa-user"></i>

Profil Bilgileri

</h3>

<div class="info-item">

<span>Ad Soyad</span>

<strong>

<?= htmlspecialchars($user["first_name"]." ".$user["last_name"]) ?>

</strong>

</div>

<div class="info-item">

<span>E-Posta</span>

<strong>

<?= htmlspecialchars($user["email"]) ?>

</strong>

</div>

</div>




<!-- Şifre -->

<div class="settings-card">

<h3>

🔐 Şifre Değiştir

</h3>

<br>

<form method="POST" class="income-form">

<input
type="password"
name="old_password"
placeholder="Mevcut Şifre"
required>

<input
type="password"
name="new_password"
placeholder="Yeni Şifre"
required>

<input
type="password"
name="again_password"
placeholder="Yeni Şifre Tekrar"
required>

<button
class="auth-btn"
name="change_password">

Şifreyi Güncelle

</button>

</form>

</div>


<!-- Hesabı Sil -->

<div class="settings-card danger-card">

<h3 style="color:red;">

⚠️ Hesabı Sil

</h3>

<p>

Hesabınızı sildiğinizde tüm gelir, gider ve hedef kayıtlarınız kalıcı olarak silinir.

</p>

<br>

<form method="POST" class="income-form">

<input
type="password"
name="delete_password"
placeholder="Şifrenizi Girin"
required>

<button

class="auth-btn"

name="delete_account"

onclick="return confirm('Hesabınızı silmek istediğinize emin misiniz?')">

Hesabı Sil

</button>

</form>

</div>

</div>

</body>

</html>