<?php

session_start();

if(!isset($_SESSION["id"])){

    header("Location: login.php");
    exit;

}

require_once "config/database.php";

$user_id = $_SESSION["id"];
$userName = $_SESSION["first_name"];


/* Toplam Gelir */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM incomes
WHERE user_id=?
");

$stmt->execute([$user_id]);
$totalIncome = $stmt->fetch()["total"];


/* Toplam Gider */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM expenses
WHERE user_id=?
");

$stmt->execute([$user_id]);
$totalExpense = $stmt->fetch()["total"];


/* Bakiye */

$balance = $totalIncome - $totalExpense;


/* Son İşlemler */

$stmt = $pdo->prepare("
SELECT title,amount,income_date AS tdate,'Gelir' AS type
FROM incomes
WHERE user_id=?

UNION ALL

SELECT title,amount,expense_date AS tdate,'Gider' AS type
FROM expenses
WHERE user_id=?

ORDER BY tdate DESC
LIMIT 5
");

$stmt->execute([
    $user_id,
    $user_id
]);

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* Güncel Döviz Kuru */

$currencyData = file_get_contents(
"https://api.frankfurter.app/latest?from=TRY&to=USD,EUR"
);

$currency = json_decode($currencyData,true);

$usdRate = $currency["rates"]["USD"];
$eurRate = $currency["rates"]["EUR"];


/* TL -> USD / EUR */

$incomeUSD = $totalIncome * $usdRate;
$incomeEUR = $totalIncome * $eurRate;

$expenseUSD = $totalExpense * $usdRate;
$expenseEUR = $totalExpense * $eurRate;

$balanceUSD = $balance * $usdRate;
$balanceEUR = $balance * $eurRate;

$rateDate = $currency["date"];
/* Bu Ay Gelir */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0)
FROM incomes
WHERE user_id=?
AND MONTH(income_date)=MONTH(CURDATE())
AND YEAR(income_date)=YEAR(CURDATE())
");

$stmt->execute([$user_id]);
$thisMonthIncome = $stmt->fetchColumn();


/* Bu Ay Gider */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0)
FROM expenses
WHERE user_id=?
AND MONTH(expense_date)=MONTH(CURDATE())
AND YEAR(expense_date)=YEAR(CURDATE())
");

$stmt->execute([$user_id]);
$thisMonthExpense = $stmt->fetchColumn();

$thisMonthBalance = $thisMonthIncome - $thisMonthExpense;


/* En Çok Harcanan Kategori */

$stmt = $pdo->prepare("
SELECT
category,
SUM(amount) AS total
FROM expenses
WHERE user_id=?
GROUP BY category
ORDER BY total DESC
LIMIT 1
");

$stmt->execute([$user_id]);

$topCategory = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
SELECT COUNT(*)
FROM goals
WHERE user_id=?
");

$stmt->execute([$user_id]);

$goalCount = $stmt->fetchColumn();


?>
<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Anasayfa | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<header>

<h2>

Hoş Geldin,

<?=htmlspecialchars($userName)?>

👋

</h2>

<a href="profil.php" class="btn-profile">
    <?= htmlspecialchars($userName) ?>
</a>



</header>

<div class="cards">

    <!-- Gelir -->
    <div class="card income">

        <div class="card-rotate" onclick="nextCurrency()">
            <i class="fa-solid fa-rotate"></i>
        </div>

        <p>Toplam Gelir</p>

        <h2 id="incomeValue">
            ₺<?= number_format($totalIncome,2,",",".") ?>
        </h2>

        <span class="card-info" id="incomeInfo">
            ≈ $<?= number_format($incomeUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($incomeEUR,2,",",".") ?>
        </span>

    </div>

    <!-- Gider -->
    <div class="card expense">

        <div class="card-rotate" onclick="nextCurrency()">
            <i class="fa-solid fa-rotate"></i>
        </div>

        <p>Toplam Gider</p>

        <h2 id="expenseValue">
            ₺<?= number_format($totalExpense,2,",",".") ?>
        </h2>

        <span class="card-info" id="expenseInfo">
            ≈ $<?= number_format($expenseUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($expenseEUR,2,",",".") ?>
        </span>

    </div>

    <!-- Bakiye -->
    <div class="card balance">

        <div class="card-rotate" onclick="nextCurrency()">
            <i class="fa-solid fa-rotate"></i>
        </div>

        <p>Güncel Bakiye</p>

        <h2 id="balanceValue">
            ₺<?= number_format($balance,2,",",".") ?>
        </h2>

        <span class="card-info" id="balanceInfo">
            ≈ $<?= number_format($balanceUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($balanceEUR,2,",",".") ?>
        </span>

    </div>

</div>
<p class="rate-date">
Kur Tarihi:
<?= date("d.m.Y",strtotime($rateDate)) ?>
</p>
<div class="dashboard-box">

<h3>
<i class="fa-solid fa-calendar-days"></i>
Bu Ay Özeti
</h3>

<div class="summary-grid">

<div>
<span>Gelir</span>
<h2>₺<?= number_format($thisMonthIncome,2,",",".") ?></h2>
</div>

<div>
<span>Gider</span>
<h2>₺<?= number_format($thisMonthExpense,2,",",".") ?></h2>
</div>

<div>
<span>Tasarruf</span>
<h2>₺<?= number_format($thisMonthBalance,2,",",".") ?></h2>
</div>
</div>

<div class="dashboard-box">

<h3>
<i class="fa-solid fa-bullseye"></i>
Tasarruf Hedefleri
</h3>

<h2><?= $goalCount ?></h2>

<p>Aktif hedef bulunuyor.</p>

</div>
</div>
<div class="dashboard-box">

<h3>
<i class="fa-solid fa-fire"></i>
En Çok Harcanan
</h3>

<?php if($topCategory){ ?>

<p><?= htmlspecialchars($topCategory["category"]) ?></p>

<h2>
₺<?= number_format($topCategory["total"],2,",",".") ?>
</h2>

<?php }else{ ?>

<p>Henüz harcama bulunmuyor.</p>

<?php } ?>

</div>
<br>

<div class="recent">

<div class="recent-header">

<h3>Son İşlemler</h3>

<a href="reports.php" class="mini-btn">
Tümünü Gör
</a>

</div>
<br>
<table>
    <tr>
        <th>Tür</th>
        <th>Başlık</th>
        <th>Tarih</th>
        <th>Tutar</th>
</tr>

<?php if(Count($transactions)>0):?>
    <?php foreach($transactions as $item): ?>
        <tr>
            <td>
                <?php
                if($item["type"] == "Gelir"){
                    echo"Gelir";
                }
                else{
                    echo"Gider";
                }
                ?>
                </td>
                <td>
                    <?= htmlspecialchars($item["title"])?>
    </td>
    <td>
        <?= date("d.m.Y" ,strtotime($item["tdate"]))?>
    </td>
   

<td>

    <?php if($item["type"] == "Gelir"){ ?>

        <span class="status-income">
             + ₺ <?= number_format($item["amount"], 2, ",", ".") ?>
        </span>

    <?php } else { ?>

        <span class="status-expense">
            - ₺ <?= number_format($item["amount"], 2, ",", ".") ?>
        </span>

    <?php } ?>

</td>
    </tr>
    <?php endforeach; ?>
    <?php else:?>
        <tr>
            <td colspan="4" style="text-align:center">

    Henüz işlem bulunmuyor
    </td>
        </tr>
    <?php endif ;?>
    </table>

</div>

<script>
let currency = "TRY";

const values = {
    income: {
        TRY: <?= $totalIncome ?>,
        USD: <?= $incomeUSD ?>,
        EUR: <?= $incomeEUR ?>
    },
    expense: {
        TRY: <?= $totalExpense ?>,
        USD: <?= $expenseUSD ?>,
        EUR: <?= $expenseEUR ?>
    },
    balance: {
        TRY: <?= $balance ?>,
        USD: <?= $balanceUSD ?>,
        EUR: <?= $balanceEUR ?>
    }
};

function formatMoney(value){
    return Number(value).toLocaleString("tr-TR",{
        minimumFractionDigits:2,
        maximumFractionDigits:2
    });
}

function nextCurrency(){

    if(currency=="TRY"){
        currency="USD";
    }else if(currency=="USD"){
        currency="EUR";
    }else{
        currency="TRY";
    }

    const symbol={
        TRY:"₺",
        USD:"$",
        EUR:"€"
    };

    document.getElementById("incomeValue").innerHTML =
        symbol[currency]+formatMoney(values.income[currency]);

    document.getElementById("expenseValue").innerHTML =
        symbol[currency]+formatMoney(values.expense[currency]);

    document.getElementById("balanceValue").innerHTML =
        symbol[currency]+formatMoney(values.balance[currency]);

    updateSmallText();
}

function updateSmallText(){

    const list=["TRY","USD","EUR"];

    let other=list.filter(x=>x!=currency);

    const s={
        TRY:"₺",
        USD:"$",
        EUR:"€"
    };

    document.getElementById("incomeInfo").innerHTML=
        "≈ "+s[other[0]]+formatMoney(values.income[other[0]])+
        " | ≈ "+s[other[1]]+formatMoney(values.income[other[1]]);

    document.getElementById("expenseInfo").innerHTML=
        "≈ "+s[other[0]]+formatMoney(values.expense[other[0]])+
        " | ≈ "+s[other[1]]+formatMoney(values.expense[other[1]]);

    document.getElementById("balanceInfo").innerHTML=
        "≈ "+s[other[0]]+formatMoney(values.balance[other[0]])+
        " | ≈ "+s[other[1]]+formatMoney(values.balance[other[1]]);
}
</script>

</body>

</html>