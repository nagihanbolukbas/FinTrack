<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil | FinTrack</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

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
        <h2>
            <?= htmlspecialchars($user["first_name"]." ".$user["last_name"]) ?>
        </h2>

        <p>
            <i class="fa-solid fa-envelope"></i>
            <?= htmlspecialchars($user["email"]) ?>
        </p>
    </div>

</div>

<div class="profil-grid">

    <div class="info-box">
        <i class="fa-solid fa-envelope"></i>

        <h4>E-Posta</h4>

        <span>
            <?= htmlspecialchars($user["email"]) ?>
        </span>
    </div>

    <div class="info-box">
        <i class="fa-solid fa-calendar"></i>

        <h4>Kayıt Tarihi</h4>

        <span>
            <?= date("d.m.Y",strtotime($user["created_at"])) ?>
        </span>
    </div>

</div>

<div class="profile-summary">

<h3>

<i class="fa-solid fa-chart-line"></i>

Hesap Özeti

</h3>

<div class="stats-grid">

<div class="stat-box">

<span>Toplam Gelir</span>

<h2>

₺<?= number_format($totalIncome,2,",",".") ?>

</h2>

</div>

<div class="stat-box">

<span>Toplam Gider</span>

<h2>

₺<?= number_format($totalExpense,2,",",".") ?>

</h2>

</div>

<div class="stat-box">

<span>Mevcut Bakiye</span>

<h2>

₺<?= number_format($balance,2,",",".") ?>

</h2>

</div>

<div class="stat-box">

<span>Hedef Sayısı</span>

<h2>

<?= $goalCount ?>

</h2>

</div>

</div>

</div>

<div class="profile-summary">

<h3>

<i class="fa-solid fa-shield-halved"></i>

Finans Analizi

</h3>

<div class="stats-grid">

<div class="stat-box">

<span>Finans Skoru</span>

<h2>

<?= $score ?>/100

</h2>

</div>

<div class="stat-box">

<span>Hesap Durumu</span>

<h2 style="color:<?= $statusColor ?>">

<?= $status ?>

</h2>

</div>

</div>

</div>

<div class="dashboard-box">

<h3>

<i class="fa-solid fa-chart-simple"></i>

Finans Skoru

</h3>

<div class="score-bar">

<div
class="score-fill"
style="width:<?= $score ?>%;background:<?= $statusColor ?>;">
</div>

</div>

<p>

<strong><?= $score ?>/100</strong>

-

<span style="color:<?= $statusColor ?>">

<?= $status ?>

</span>

</p>

</div>

</div>

</body>
</html>