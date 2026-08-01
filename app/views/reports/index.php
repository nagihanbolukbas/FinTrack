
<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>FinTrack | Raporlar</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<header class="report-header">

<div>

<h2>📊 Finansal Raporlar</h2>

<p>Gelir, gider ve finansal analizlerinizi görüntüleyin.</p>

</div>

<a href="report-pdf.php" target="_blank" class="pdf-btn">

<i class="fa-solid fa-file-pdf"></i>

PDF İndir

</a>

</header>

<?php

$aylar = [

1=>"Ocak",
2=>"Şubat",
3=>"Mart",
4=>"Nisan",
5=>"Mayıs",
6=>"Haziran",
7=>"Temmuz",
8=>"Ağustos",
9=>"Eylül",
10=>"Ekim",
11=>"Kasım",
12=>"Aralık"

];

?>

<form method="GET" class="report-filter">

<select name="month">

<option value="">Tüm Aylar</option>

<?php foreach($aylar as $no=>$ad){ ?>

<option value="<?= $no ?>" <?= ($month==$no) ? "selected" : "" ?>>

<?= $ad ?>

</option>

<?php } ?>

</select>

<button class="filter-btn">

Filtrele

</button>

</form>
<?php if($balance >= 0){ ?>

<p style="color:#16A34A;">
Geliriniz giderinizden yüksek.
</p>

<?php }else{ ?>

<p style="color:#DC2626;">
Gideriniz gelirinizi geçti.
</p>

<?php } ?>
<div class="chart-grid">

<div class="chart-card">

<h3>💚 Gelir Kategorileri</h3>

<canvas id="incomeChart"></canvas>

</div>


<div class="chart-card">

<h3>❤️ Gider Kategorileri</h3>

<canvas id="expenseChart"></canvas>

</div>

</div>

<div class="report-lists">
    <div class="report-box">

    <h3>💚 Gelir İşlemleri</h3>

    <?php if(count($incomeList)>0){ ?>

        <?php foreach($incomeList as $row){ ?>

        <div class="report-item">

            <div>

    <strong><?= htmlspecialchars($row["title"]) ?></strong>

    <br>

    <span style="color:#64748B;font-size:14px;">
        <?= htmlspecialchars($row["category"]) ?>
    </span>

    <br>

    <small>
        <?= date("d.m.Y", strtotime($row["income_date"])) ?>
    </small>

</div>

            <strong class="status-income">
                ₺<?= number_format($row["amount"],2,",",".") ?>
            </strong>

        </div>

        <?php } ?>

    <?php }else{ ?>

        <p>Gelir kaydı bulunamadı.</p>

    <?php } ?>

</div>


<div class="report-box">

    <h3>❤️ Gider İşlemleri</h3>

    <?php if(count($expenseList)>0){ ?>

        <?php foreach($expenseList as $row){ ?>

        <div class="report-item">

            <div>

                <strong><?= htmlspecialchars($row["category"]) ?></strong>

                <br>

                <small>
                    <?= date("d.m.Y", strtotime($row["expense_date"])) ?>
                </small>

            </div>

            <strong class="status-expense">
                ₺<?= number_format($row["amount"],2,",",".") ?>
            </strong>

        </div>

        <?php } ?>

    <?php }else{ ?>

        <p>Gider kaydı bulunamadı.</p>

    <?php } ?>

</div>

</div>


<div class="finance-analysis">

<h3>
<i class="fa-solid fa-chart-line"></i>
Finansal Durum Analizi
</h3>

<div class="analysis-grid">

<div class="analysis-item">
<span>Finans Durumu</span>
<strong><?= $balance>=0 ? "İyi" : "Riskli" ?></strong>
</div>

<div class="analysis-item">
<span>Tasarruf Oranı</span>
<strong>%<?= number_format($savingRate,1,",",".") ?></strong>
</div>

<div class="analysis-item">
<span>En Çok Harcama</span>
<strong><?= htmlspecialchars($categoryName) ?></strong>
</div>

<div class="analysis-item">
<span>Finans Skoru</span>
<strong><?= $score ?>/100</strong>
</div>

</div>

</div>


<script>

const incomeLabels=[
<?php foreach($incomeSummary as $row){ ?>
'<?= htmlspecialchars($row["category"]) ?>',
<?php } ?>
];

const incomeValues=[
<?php foreach($incomeSummary as $row){ ?>
<?= $row["total"] ?>,
<?php } ?>
];

new Chart(document.getElementById("incomeChart"),{

    type:"doughnut",

    data:{

        labels:incomeLabels,

        datasets:[{

            data:incomeValues,

            backgroundColor:[
                "#16A34A",
                "#22C55E",
                "#4ADE80",
                "#86EFAC",
                "#BBF7D0",
                "#DCFCE7"
            ],

            borderWidth:0

        }]

    },

    options:{

        cutout:"72%",

        plugins:{
            legend:{
                position:"bottom",
                labels:{
                    usePointStyle:true,
                    pointStyle:"circle",
                    padding:18
                }
            }
        }

    }

});


const expenseLabels=[
<?php foreach($expenseSummary as $row){ ?>
'<?= htmlspecialchars($row["category"]) ?>',
<?php } ?>
];

const expenseValues=[
<?php foreach($expenseSummary as $row){ ?>
<?= $row["total"] ?>,
<?php } ?>
];

new Chart(document.getElementById("expenseChart"),{

    type:"doughnut",

    data:{

        labels:expenseLabels,

        datasets:[{

            data:expenseValues,

            backgroundColor: [
    "#FF6B00",
    "#FF8A00",
    "#FFA726",
    "#FFB74D",
    "#FFCC80",
    "#FFE0B2",
    "#E65100"
],

            borderWidth:0

        }]

    },

    options:{

        cutout:"72%",

        plugins:{
            legend:{
                position:"bottom",
                labels:{
                    usePointStyle:true,
                    pointStyle:"circle",
                    padding:18
                }
            }
        }

    }

});

</script>

</div>

</body>
</html>