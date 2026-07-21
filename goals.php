<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$user_id = $_SESSION["id"];

/* ------------------------
   HEDEF EKLE
------------------------ */

if(isset($_POST["save"])){

    $title = trim($_POST["title"]);
    $category = $_POST["category"];
    $target = $_POST["target_amount"];
    $saved = $_POST["saved_amount"];
    $deadline = $_POST["deadline"];

    $stmt = $pdo->prepare("
        INSERT INTO goals
        (user_id,title,category,target_amount,saved_amount,deadline)
        VALUES(?,?,?,?,?,?)
    ");

    $stmt->execute([
        $user_id,
        $title,
        $category,
        $target,
        $saved,
        $deadline
    ]);

    header("Location: goals.php");
    exit;
}

/* ------------------------
   PARA EKLE
------------------------ */

if(isset($_POST["add_money"])){

    $goal_id = $_POST["goal_id"];
    $amount = $_POST["amount"];

    $stmt = $pdo->prepare("
        UPDATE goals
        SET saved_amount=saved_amount+?
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([
        $amount,
        $goal_id,
        $user_id
    ]);

    header("Location: goals.php");
    exit;
}

/* ------------------------
   PARA ÇIKAR
------------------------ */

if(isset($_POST["remove_money"])){

    $goal_id = $_POST["goal_id"];
    $amount = $_POST["amount"];

    $stmt = $pdo->prepare("
        UPDATE goals
        SET saved_amount=
        CASE
            WHEN saved_amount-? < 0 THEN 0
            ELSE saved_amount-?
        END
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([
        $amount,
        $amount,
        $goal_id,
        $user_id
    ]);

    header("Location: goals.php");
    exit;
}

/* ------------------------
   HEDEFLER
------------------------ */

$stmt = $pdo->prepare("
SELECT *
FROM goals
WHERE user_id=?
ORDER BY id DESC
");

$stmt->execute([$user_id]);

$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------
   İSTATİSTİKLER
------------------------ */

$totalGoals = count($goals);

$completedGoals = 0;

$nearestGoal = null;
$highestPercent = 0;

foreach($goals as $goal){

    if($goal["target_amount"]>0){

        $percent = ($goal["saved_amount"]/$goal["target_amount"])*100;

    }else{

        $percent = 0;

    }

    if($percent>=100){
        $completedGoals++;
    }

    if($percent>$highestPercent && $percent<100){

        $highestPercent = $percent;
        $nearestGoal = $goal;

    }

}
?>
<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Finansal Hedefler | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>🎯 Finansal Hedefler</h2>

<br>

<!-- İstatistik Kartları -->

<div class="cards">

<div class="card balance">

<p>Aktif Hedef</p>

<h2><?= $totalGoals ?></h2>

<span class="card-info">Toplam oluşturulan hedef</span>

</div>

<div class="card income">

<p>Tamamlanan</p>

<h2><?= $completedGoals ?></h2>

<span class="card-info">Başarıyla tamamlandı</span>

</div>

<div class="card expense">

<p>En Yakın Hedef</p>

<h2>

<?= $nearestGoal ? htmlspecialchars($nearestGoal["title"]) : "-" ?>

</h2>

<span class="card-info">

<?php
if($nearestGoal){

$percent=($nearestGoal["saved_amount"]/$nearestGoal["target_amount"])*100;

echo "%".number_format($percent,0);

}else{

echo "Henüz hedef yok";

}
?>

</span>

</div>

</div>

<!-- Hedef Ekle -->

<form method="POST" class="income-form">

<input
type="text"
name="title"
placeholder="Hedef Adı"
required>

<select name="category" required>

<option value="">Kategori</option>

<option value="Ev">🏠 Ev</option>

<option value="Araba">🚗 Araba</option>

<option value="Telefon">📱 Telefon</option>

<option value="Bilgisayar">💻 Bilgisayar</option>

<option value="Tatil">✈️ Tatil</option>

<option value="Eğitim">🎓 Eğitim</option>

<option value="Düğün">💍 Düğün</option>

<option value="Diğer">📌 Diğer</option>

</select>

<input
type="number"
step="0.01"
name="target_amount"
placeholder="Hedef Tutar"
required>

<input
type="number"
step="0.01"
name="saved_amount"
placeholder="Biriken Tutar"
required>

<input
type="date"
name="deadline"
required>

<button class="auth-btn" name="save">

<i class="fa-solid fa-plus"></i>

Hedef Ekle

</button>

</form>

<div class="recent">

<h3>🎯 Hedeflerim</h3>

<?php foreach($goals as $goal){

$percent=0;

if($goal["target_amount"]>0){

$percent=($goal["saved_amount"]/$goal["target_amount"])*100;

}

if($percent>100){
$percent=100;
}

$remaining=$goal["target_amount"]-$goal["saved_amount"];

$days=ceil((strtotime($goal["deadline"])-time())/86400);

?>

<div class="goal-card">

<div class="goal-header">

<h3>

<?php

switch($goal["category"]){

case "Ev": echo "🏠 "; break;
case "Araba": echo "🚗 "; break;
case "Telefon": echo "📱 "; break;
case "Bilgisayar": echo "💻 "; break;
case "Tatil": echo "✈️ "; break;
case "Eğitim": echo "🎓 "; break;
case "Düğün": echo "💍 "; break;
default: echo "📌 ";

}

?>

<?= htmlspecialchars($goal["title"]) ?>

</h3>

<span class="percent">

%<?= number_format($percent,0) ?>

</span>

</div>

<div class="progress">

<div class="progress-bar"

style="width:<?= $percent ?>%">

</div>

</div>

<div class="money-info">

<span>

Biriken

<br>

<strong>

₺<?= number_format($goal["saved_amount"],2,",",".") ?>

</strong>

</span>

<span>

Hedef

<br>

<strong>

₺<?= number_format($goal["target_amount"],2,",",".") ?>

</strong>

</span>

</div>

<p class="remaining">

💰 Kalan:

<strong>

₺<?= number_format(max(0,$remaining),2,",",".") ?>

</strong>

</p>

<p class="remaining">

📅 Son Tarih:

<strong>

<?= date("d.m.Y",strtotime($goal["deadline"])) ?>

</strong>

</p>

<p class="remaining">
💰 Kalan:
<strong>
₺<?= number_format(max(0,$remaining),2,",",".") ?>
</strong>
</p>

<p class="remaining">
📅 Son Tarih:
<strong>
<?= date("d.m.Y",strtotime($goal["deadline"])) ?>
</strong>
</p>

<p class="remaining">
⏳
<?php
if($days >= 0){
    echo $days . " gün kaldı";
}else{
    echo "Süre doldu";
}
?>
</p>

<?php if($remaining <= 0){ ?>

<p class="success">
🏆 Hedef Tamamlandı!
</p>

<?php } ?>

<div style="display:flex;gap:10px;margin-top:18px;">

    <a href="edit_goal.php?id=<?= $goal["id"] ?>" class="mini-btn">
        <i class="fa-solid fa-pen"></i> Düzenle
    </a>

    <a href="delete_goal.php?id=<?= $goal["id"] ?>"
       class="mini-btn"
       style="background:#EF4444;"
       onclick="return confirm('Bu hedef silinsin mi?')">

        <i class="fa-solid fa-trash"></i> Sil

    </a>

</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>