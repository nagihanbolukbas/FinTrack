<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if(isset($_POST["save"])){

    $title = trim($_POST["title"]);
    $category=$_POST["category"];
    $amount = $_POST["amount"];
    $date = $_POST["expenses_date"];

    $stmt = $pdo->prepare("
        INSERT INTO expenses(user_id,title,category,amount,expense_date)
        VALUES(?,?,?,?,?)
    ");

    $stmt->execute([
        $_SESSION["id"],
        $title,
        $category,
        $amount,
        $date
    ]);

    header("Location: expenses.php");
    exit;
}
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
<body>

<?php include "includes/sidebar.php"; ?>
 
<div class="content">

<h2>💸 Gider Ekle</h2>
<br>


</body>
</html>
