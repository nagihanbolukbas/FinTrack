<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Geliri Düzenle | FinTrack</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>
<i class="fa-solid fa-pen-to-square"></i>
Geliri Düzenle
</h2>

<br>

<form method="POST" class="income-form">

<select name="category" required>

<option value="Maaş" <?= $income["category"]=="Maaş" ? "selected" : "" ?>>
💼 Maaş
</option>

<option value="Burs" <?= $income["category"]=="Burs" ? "selected" : "" ?>>
🎓 Burs
</option>

<option value="Freelance" <?= $income["category"]=="Freelance" ? "selected" : "" ?>>
💻 Freelance
</option>

<option value="Yatırım" <?= $income["category"]=="Yatırım" ? "selected" : "" ?>>
📈 Yatırım
</option>

<option value="Diğer" <?= $income["category"]=="Diğer" ? "selected" : "" ?>>
📌 Diğer
</option>

</select>

<input
type="number"
step="0.01"
name="amount"
value="<?= htmlspecialchars($income["amount"]) ?>"
required>

<input
type="date"
name="income_date"
value="<?= htmlspecialchars($income["income_date"]) ?>"
required>

<div style="display:flex;gap:15px;">

<button type="submit" name="save" class="auth-btn">
<i class="fa-solid fa-floppy-disk"></i>
Kaydet
</button>

<a href="incomes.php" class="mini-btn">
<i class="fa-solid fa-arrow-left"></i>
Geri Dön
</a>

</div>

</form>

</div>

</body>
</html>