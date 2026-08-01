<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Gelir Ekle | FinTrack</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>💰 Gelir Ekle</h2>

<br>

<form method="POST" class="income-form">

<select name="category" id="category" required>

<option value="">Kategori Seç</option>

<option value="Maaş">💼 Maaş</option>
<option value="Burs">🎓 Burs</option>
<option value="Freelance">💻 Freelance</option>
<option value="Yatırım">📈 Yatırım</option>
<option value="Diğer">📌 Diğer</option>
<option value="Hedef">🎯 Hedefe Para Ekle</option>

</select>

<input
type="number"
step="0.01"
name="amount"
placeholder="Tutar"
required>

<input
type="date"
id="incomeDate"
name="income_date">

<div id="goalArea" style="display:none;">

<select name="goal_id">

<option value="">Hedef Seç</option>

<?php foreach($goalList as $goal){ ?>

<option value="<?= $goal["id"] ?>">

<?= htmlspecialchars($goal["title"]) ?>

</option>

<?php } ?>

</select>

</div>

<button name="save" class="auth-btn">

Geliri Kaydet

</button>

</form>

<div class="recent">

<h3>📌 Son Eklenen Gelirler</h3>

<?php if(count($recent_incomes)>0){ ?>

<?php foreach($recent_incomes as $income){ ?>

<div class="goal-card" style="display:flex;justify-content:space-between;align-items:center;">

<div>

<h4><?= htmlspecialchars($income["title"]) ?></h4>

<p>

<i class="fa-solid fa-tag"></i>

<?= htmlspecialchars($income["category"]) ?>

</p>

<strong style="color:#16A34A;">

+₺<?= number_format($income["amount"],2,",",".") ?>

</strong>

<br>

<small>

<?= date("d.m.Y",strtotime($income["income_date"])) ?>

</small>

</div>

<div style="display:flex;gap:10px;">

<a
href="edit_income.php?id=<?= $income["id"] ?>"
class="mini-btn">

<i class="fa-solid fa-pen"></i>

Düzenle

</a>

<a
href="delete_income.php?id=<?= $income["id"] ?>"
class="mini-btn"
style="background:#EF4444;"
onclick="return confirm('Bu geliri silmek istediğinize emin misiniz?')">

<i class="fa-solid fa-trash"></i>

Sil

</a>

</div>

</div>

<?php } ?>

<?php }else{ ?>

<p>Henüz gelir kaydı bulunmuyor.</p>

<?php } ?>

</div>

</div>

<script>

const category=document.getElementById("category");
const goalArea=document.getElementById("goalArea");
const incomeDate=document.getElementById("incomeDate");

category.addEventListener("change",function(){

    if(this.value=="Hedef"){

        goalArea.style.display="block";

        incomeDate.style.display="none";
        incomeDate.required=false;

    }else{

        goalArea.style.display="none";

        incomeDate.style.display="block";
        incomeDate.required=true;

    }

});

</script>

</body>
</html>