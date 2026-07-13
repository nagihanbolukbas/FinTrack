<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$month = isset($_GET["month"]) ? $_GET["month"] : "";

$params = [$_SESSION["id"]];

$incomeSql = "
SELECT IFNULL(SUM(amount),0) total
FROM incomes
WHERE user_id=?
";

if($month!=""){
    $incomeSql.=" AND MONTH(income_date)=?";
    $params[]=$month;
}

$stmt=$pdo->prepare($incomeSql);
$stmt->execute($params);
$totalIncome=$stmt->fetch(PDO::FETCH_ASSOC)["total"];

/* -------------------- */

$params=[ $_SESSION["id"] ];

$expenseSql="
SELECT IFNULL(SUM(amount),0) total
FROM expenses
WHERE user_id=?
";

if($month!=""){
    $expenseSql.=" AND MONTH(expense_date)=?";
    $params[]=$month;
}

$stmt=$pdo->prepare($expenseSql);
$stmt->execute($params);
$totalExpense=$stmt->fetch(PDO::FETCH_ASSOC)["total"];

$balance=$totalIncome-$totalExpense;

/* -------------------- */
/* Gelir Kategorileri */

$params=[ $_SESSION["id"] ];

$sql="
SELECT category,
SUM(amount) total
FROM incomes
WHERE user_id=?
";

if($month!=""){
    $sql.=" AND MONTH(income_date)=?";
    $params[]=$month;
}

$sql.=" GROUP BY category";

$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$incomeCategories=$stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------- */
/* Gider Kategorileri */

$params=[ $_SESSION["id"] ];

$sql="
SELECT category,
SUM(amount) total
FROM expenses
WHERE user_id=?
";

if($month!=""){
    $sql.=" AND MONTH(expense_date)=?";
    $params[]=$month;
}

$sql.=" GROUP BY category";

$stmt=$pdo->prepare($sql);
$stmt->execute($params);
$expenseCategories=$stmt->fetchAll(PDO::FETCH_ASSOC);

/* -------------------- */
/* En yüksek harcama */

$highestCategory="-";
$highestAmount=0;

foreach($expenseCategories as $row){

    if($row["total"]>$highestAmount){

        $highestAmount=$row["total"];
        $highestCategory=$row["category"];

    }

}

/* -------------------- */

$savingRate=0;

if($totalIncome>0){

    $savingRate=($balance/$totalIncome)*100;

}

$score=50;

if($balance>0) $score+=20;
if($savingRate>20) $score+=20;
if($savingRate>40) $score+=10;

if($score>100) $score=100;

?>
<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Raporlar</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<header>

<h2>📊 Finansal Raporlar</h2>


</header>

<form method="GET" class="income-form">

<select name="month">

<option value="">Tüm Aylar</option>

<?php
for($i=1;$i<=12;$i++){
?>

<option
value="<?=$i?>"
<?=($month==$i)?"selected":"";?>>

<?=date("F",mktime(0,0,0,$i,1));?>

</option>

<?php } ?>

</select>

<button class="auth-btn">

Filtrele

</button>

</form>

<div class="report-cards">



</div>
<div class="chart-grid">

    <div class="chart-card">

        <h3>💰 Gelir Kategorileri</h3>

        <canvas id="incomeChart"></canvas>

    </div>

    <div class="chart-card">

        <h3>💸 Gider Kategorileri</h3>

        <canvas id="expenseChart"></canvas>

    </div>

</div>

<div class="report-lists">

    <div class="report-box">

        <h3>Gelir Dağılımı</h3>

        <?php foreach($incomeCategories as $row){ ?>

        <div class="report-item">

            <span><?= htmlspecialchars($row["category"]) ?></span>

            <strong>
                ₺<?= number_format($row["total"],2,",",".") ?>
            </strong>

        </div>

        <?php } ?>

    </div>

    <div class="report-box">

        <h3>Gider Dağılımı</h3>

        <?php foreach($expenseCategories as $row){ ?>

        <div class="report-item">

            <span><?= htmlspecialchars($row["category"]) ?></span>

            <strong>
                ₺<?= number_format($row["total"],2,",",".") ?>
            </strong>

        </div>

        <?php } ?>

    </div>

</div>





</div>

</body>

</html>