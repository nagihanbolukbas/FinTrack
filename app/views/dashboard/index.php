<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Anasayfa | FinTrack</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<header>

<h2> 
Hoş Geldin,
<?= htmlspecialchars($userName) ?>
👋
</h2>

<div class="header-actions">

   <div class="notification">

<i class="fa-solid fa-bell <?= count($notifications)>0 ? 'shake' : '' ?>" id="bellBtn"></i>

<?php if(count($notifications)>0){ ?>

<span class="notification-count">
<?= count($notifications) ?>
</span>

<?php } ?>

        <div class="notification-box" id="notificationBox">

            <h4>🔔 Bildirimler</h4>

            <?php if(count($notifications)>0){ ?>

                <?php foreach($notifications as $i=>$item){ ?>

<div class="notify-item" id="notify<?= $i ?>">

<span>
<?= htmlspecialchars($item) ?>
</span>

<button
class="delete-notify"
onclick="deleteNotification(event,<?= $i ?>)">
<i class="fa-solid fa-xmark"></i>
</button>

</div>

<?php } ?>

            <?php }else{ ?>

                <p>✅ Yeni bildiriminiz bulunmuyor.</p>

            <?php } ?>

            <hr>

           

        </div>

    </div>


  <div class="profile-dropdown">

    <a href="profil.php" class="btn-profile">
        <?= htmlspecialchars($userName) ?>
    </a>

    <div class="dropdown-menu">
        <a href="profil.php">
            <i class="fa-solid fa-user"></i>
            Profil
        </a>

        <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            Çıkış Yap
        </a>


    </div>

</div>

</div>
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
Güncel Kur Tarihi:
<?= date("d.m.Y", strtotime($rateDate)); ?>
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

<?php if($goalCount>0){ ?>

<p>Aktif hedef bulunuyor.</p>

<?php }else{ ?>

<p>Henüz hedef oluşturmadınız.</p>

<?php } ?>

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
const bell = document.getElementById("bellBtn");
const box = document.getElementById("notificationBox");

bell.addEventListener("click", function(e){

    e.stopPropagation();

    if(box.style.display=="block"){
        box.style.display="none";
    }else{
        box.style.display="block";
    }

});

document.addEventListener("click", function(e){

    if(!e.target.closest(".notification")){
        box.style.display="none";
    }

});
function deleteNotification(event, id){

    event.stopPropagation();

    const item = document.getElementById("notify" + id);

    if(item){
        item.remove();
    }

    let badge = document.querySelector(".notification-count");

    if(badge){

        let count = parseInt(badge.innerText) - 1;

        if(count <= 0){

            badge.remove();
            document.getElementById("bellBtn").classList.remove("shake");

            document.getElementById("notificationBox").innerHTML = `
                <h4>🔔 Bildirimler</h4>
                <p>✅ Yeni bildiriminiz bulunmuyor.</p>
            `;

        }else{

            badge.innerText = count;

        }

    }

}
</script>

</body>

</html>