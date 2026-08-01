<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Hedef Düzenle</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>🎯 Hedef Düzenle</h2>

<br>

<form method="POST" class="income-form">

<input
type="text"
name="title"
value="<?= htmlspecialchars($goal["title"]) ?>"
placeholder="Hedef Adı"
required>

<select name="category" required>

<option value="Ev" <?= $goal["category"]=="Ev"?"selected":"" ?>>🏠 Ev</option>
<option value="Araba" <?= $goal["category"]=="Araba"?"selected":"" ?>>🚗 Araba</option>
<option value="Telefon" <?= $goal["category"]=="Telefon"?"selected":"" ?>>📱 Telefon</option>
<option value="Bilgisayar" <?= $goal["category"]=="Bilgisayar"?"selected":"" ?>>💻 Bilgisayar</option>
<option value="Tatil" <?= $goal["category"]=="Tatil"?"selected":"" ?>>✈️ Tatil</option>
<option value="Eğitim" <?= $goal["category"]=="Eğitim"?"selected":"" ?>>🎓 Eğitim</option>
<option value="Düğün" <?= $goal["category"]=="Düğün"?"selected":"" ?>>💍 Düğün</option>
<option value="Diğer" <?= $goal["category"]=="Diğer"?"selected":"" ?>>📌 Diğer</option>

</select>

<input
type="number"
step="0.01"
name="target_amount"
value="<?= $goal["target_amount"] ?>"
required>

<input
type="number"
step="0.01"
name="saved_amount"
value="<?= $goal["saved_amount"] ?>"
required>

<input
type="date"
name="deadline"
value="<?= $goal["deadline"] ?>"
required>

<div style="display:flex;gap:15px">

<button class="auth-btn" name="save">
<i class="fa-solid fa-floppy-disk"></i>
Kaydet
</button>

<a href="goals.php" class="mini-btn">
<i class="fa-solid fa-arrow-left"></i>
Geri Dön
</a>

</div>

</form>