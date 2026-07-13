<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

/* Kullanıcı */

$stmt=$pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION["id"]]);
$user=$stmt->fetch(PDO::FETCH_ASSOC);

/* Gelir */

$stmt=$pdo->prepare("SELECT IFNULL(SUM(amount),0) total FROM incomes WHERE user_id=?");
$stmt->execute([$_SESSION["id"]]);
$totalIncome=$stmt->fetch()["total"];

/* Gider */

$stmt=$pdo->prepare("SELECT IFNULL(SUM(amount),0) total FROM expenses WHERE user_id=?");
$stmt->execute([$_SESSION["id"]]);
$totalExpense=$stmt->fetch()["total"];

$balance=$totalIncome-$totalExpense;
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">
<div class="profil-header">

    <div class="profil-avatar">
        <?= strtoupper(substr($user["first_name"],0,1)) ?>
    </div>

    <div class="profil-text">
        <h2><?= htmlspecialchars($user["first_name"]." ".$user["last_name"]) ?></h2>

        <p>
            <i class="fa-solid fa-envelope"></i>
            <?= htmlspecialchars($user["email"]) ?>
        </p>
    </div>

</div>

<div class="profil-grid">

    <div class="info-box">
        <i class="fa-solid fa-user"></i>
        <h4>Ad</h4>
        <span><?= htmlspecialchars($user["first_name"]) ?></span>
    </div>

    <div class="info-box">
        <i class="fa-solid fa-user"></i>
        <h4>Soyad</h4>
        <span><?= htmlspecialchars($user["last_name"]) ?></span>
    </div>

    <div class="info-box">
        <i class="fa-solid fa-envelope"></i>
        <h4>E-Posta</h4>
        <span><?= htmlspecialchars($user["email"]) ?></span>
    </div>

    <div class="info-box">
        <i class="fa-solid fa-calendar"></i>
        <h4>Kayıt Tarihi</h4>
        <span><?= date("d.m.Y", strtotime($user["created_at"])) ?></span>
    </div>

</div>

