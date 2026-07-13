<?php


session_start();

if(!isset($_SESSION["id"])){

    header("Location: login.php");
    exit;

}

require_once "config/database.php";

$userName=$_SESSION["first_name"];

/* Toplam Gelir */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM incomes
WHERE user_id=?
");

$stmt->execute([$_SESSION["id"]]);
$totalIncome = $stmt->fetch()["total"];

/* Toplam Gider */

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0) AS total
FROM expenses
WHERE user_id=?
");

$stmt->execute([$_SESSION["id"]]);
$totalExpense = $stmt->fetch()["total"];

$stmt->execute([$_SESSION["id"]]);
$totalExpense = $stmt->fetch()["total"];

/* Güncel Bakiye */
/* Bakiye */
$balance = $totalIncome - $totalExpense;

/* Son işlemler */
$transactions = [];

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
    $_SESSION["id"],
    $_SESSION["id"]
]);

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* Güncel kur (şimdilik sabit) */
$usdRate = 40.20;
$eurRate = 47.10;

/* Gelir */
$incomeUSD = $totalIncome / $usdRate;
$incomeEUR = $totalIncome / $eurRate;

/* Gider */
$expenseUSD = $totalExpense / $usdRate;
$expenseEUR = $totalExpense / $eurRate;

/* Bakiye */
$balanceUSD = $balance / $usdRate;
$balanceEUR = $balance / $eurRate;
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



</header>

<div class="cards">

    <div class="card income">
        <p>Toplam Gelir</p>
        <h2>₺<?= number_format($totalIncome,2,",",".") ?></h2>

        <span class="card-info">
            ≈ $<?= number_format($incomeUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($incomeEUR,2,",",".") ?>
        </span>
    </div>

    <div class="card expense">
        <p>Toplam Gider</p>
        <h2>₺<?= number_format($totalExpense,2,",",".") ?></h2>

        <span class="card-info">
            ≈ $<?= number_format($expenseUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($expenseEUR,2,",",".") ?>
        </span>
    </div>

    <div class="card balance">
        <p>Güncel Bakiye</p>
        <h2>₺<?= number_format($balance,2,",",".") ?></h2>

        <span class="card-info">
            ≈ $<?= number_format($balanceUSD,2,",",".") ?>
            |
            ≈ €<?= number_format($balanceEUR,2,",",".") ?>
        </span>
    </div>

</div>

<div class="recent">

<h3> Son İşlemler</h3>
<table>
    <tr>
        <th>Tür</th>
        <th>Başlık</th>
        <th>Tarih</th>
        <th>Tutar</th>
</tr>
<td>
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
             ₺ <?= number_format($item["amount"], 2, ",", ".") ?>
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
            <td colspan="4" style="text_align:center">

    Heniz işlem bulunmuyor
    </td>
        </tr>
    </span>
    <?php endif ;?>
    </table>

</div>

</body>

</html>