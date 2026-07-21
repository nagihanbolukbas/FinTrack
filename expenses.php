<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if(isset($_POST["save"])){

    $title = $_POST["category"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $date = $_POST["expense_date"];
    $description = null;

    $stmt = $pdo->prepare("
        INSERT INTO expenses(user_id,title,amount,expense_date,category,description)
        VALUES(?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_SESSION["id"],
        $title,
        $amount,
        $date,
        $category,
        $description
    ]);

    header("Location: expenses.php");
    exit;
}

$stmt = $pdo->prepare("
SELECT *
FROM expenses
WHERE user_id=?
ORDER BY id DESC
LIMIT 10
");

$stmt->execute([$_SESSION["id"]]);

$recent_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Gider Ekle | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>💸 Gider Ekle</h2>

<br>

<form method="POST" class="income-form">

<select name="category" required>

<option value="">Kategori Seç</option>

<option value="Market">🛒 Market</option>

<option value="Fatura">💡 Fatura</option>

<option value="Ulaşım">🚌 Ulaşım</option>

<option value="Yemek">🍔 Yemek</option>

<option value="Eğitim">📚 Eğitim</option>

<option value="Sağlık">🏥 Sağlık</option>

<option value="Eğlence">🎮 Eğlence</option>

<option value="Diğer">📌 Diğer</option>

</select>

<input
type="number"
step="0.01"
name="amount"
placeholder="Tutar"
required>

<input
type="date"
name="expense_date"
required>

<button name="save" class="auth-btn">
Gideri Kaydet
</button>

</form>

<div class="recent">

<h3>📌 Son Eklenen Giderler</h3>

<?php if(count($recent_expenses)>0){ ?>

<?php foreach($recent_expenses as $expense){ ?>

<div class="goal-card" style="display:flex;justify-content:space-between;align-items:center;">

<div>

<h4><?= htmlspecialchars($expense["title"]) ?></h4>

<p>
<i class="fa-solid fa-tag"></i>
<?= htmlspecialchars($expense["category"]) ?>
</p>

<strong style="color:#EF4444;">
-₺<?= number_format($expense["amount"],2,",",".") ?>
</strong>

<br>

<small>
<?= date("d.m.Y",strtotime($expense["expense_date"])) ?>
</small>

</div>

<div style="display:flex;gap:10px;">

<a href="edit_expense.php?id=<?= $expense["id"] ?>" class="mini-btn">
<i class="fa-solid fa-pen"></i>
Düzenle
</a>

<a
href="delete_expense.php?id=<?= $expense["id"] ?>"
class="mini-btn"
style="background:#EF4444;"
onclick="return confirm('Bu gideri silmek istediğinize emin misiniz?')">

<i class="fa-solid fa-trash"></i>
Sil

</a>

</div>

</div>

<?php } ?>

<?php }else{ ?>

<p>Henüz gider kaydı bulunmuyor.</p>

<?php } ?>

</div>

</div>

</body>

</html>