<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if(isset($_POST["save"])){

    $category = $_POST["category"];
    $amount   = $_POST["amount"];
    $date     = $_POST["expense_date"];

    $stmt = $pdo->prepare("
        INSERT INTO expenses(user_id,title,category,amount,expense_date)
        VALUES(?,?,?,?,?)
    ");

    $stmt->execute([
        $_SESSION["id"],
        $category,
        $category,
        $amount,
        $date
    ]);

    header("Location: expenses.php");
    exit;
}
$stmt = $pdo->prepare("
SELECT *
FROM expenses
WHERE user_id=?
ORDER BY id DESC
LIMIT 5
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

        <div class="input-group">

            <select name="category" required>

                <option value="">Kategori Seç</option>
                <option>Market</option>
                <option>Yemek</option>
                <option>Ulaşım</option>
                <option>Eğitim</option>
                <option>Alışveriş</option>
                <option>Eğlence</option>
                <option>Sağlık</option>

            </select>

        </div>

        <div class="input-group">

            <input
                type="number"
                step="0.01"
                name="amount"
                placeholder="Tutar"
                required>

        </div>

        <div class="input-group">

            <input
                type="date"
                name="expense_date"
                required>

        </div>

        <button type="submit" name="save" class="auth-btn">
            Gideri Kaydet
        </button>
<div class="recent">

<h3>📌 Son Eklenen Giderler</h3>


<?php foreach($recent_expenses as $expense){ ?>

<div class="goal-card">

    <h4><?= htmlspecialchars($expense["title"]) ?></h4>

    <p><?= htmlspecialchars($expense["category"]) ?></p>

    <strong>
        -₺<?= number_format($expense["amount"],2,",",".") ?>
    </strong>

    <small><?= $expense["expense_date"] ?></small>

</div>

<?php } ?>
</div>

    </form>

</div>

</body>
</html>