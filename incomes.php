<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if(isset($_POST["save"])){

   $title = $_POST["category"]; // Başlık kategori olsun
$category = $_POST["category"];
$amount = $_POST["amount"];
$date = $_POST["income_date"];
$description = null; // Açıklama kullanmıyorsan

    $stmt = $pdo->prepare("
       INSERT INTO incomes(user_id,title,amount,income_date,category,description)
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
    

    header("Location: dashboard.php");
    exit;
}
$stmt=$pdo->prepare("
SELECT *
FROM incomes
WHERE user_id=?
ORDER BY id DESC
LIMIT 5
");

$stmt->execute([$_SESSION["id"]]);

$recent_incomes=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Gelir Ekle | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>



<div class="content">

<h2>💰 Gelir Ekle</h2>
<br>

<form method="POST" class="income-form">

<select name="category" required>

<option value="">Kategori Seç</option>

<option value="Maaş">
💼 Maaş
</option>

<option value="Burs">
🎓 Burs
</option>

<option value="Freelance">
💻 Freelance
</option>

<option value="Yatırım">
📈 Yatırım
</option>

<option value="Diğer">
📌 Diğer
</option>

</select>

<input
type="number"
step="0.01"
name="amount"
placeholder="Tutar"
required>


<input
type="date"
name="income_date"
required>


<button name="save" class="auth-btn">
Geliri Kaydet
</button>
<div class="recent">

<h3>📌 Son Eklenen Gelirler</h3>


<?php foreach($recent_incomes as $income){ ?>


<div class="goal-card">


<h4>
<?= htmlspecialchars($income["title"]) ?>
</h4>


<p>
<?= $income["category"] ?>
</p>


<strong>
+₺<?= number_format($income["amount"],2,",",".") ?>
</strong>


<small>
<?= $income["income_date"] ?>
</small>


</div>


<?php } ?>


</div>

</form>

</div>

</body>
</html>