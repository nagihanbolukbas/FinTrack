<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

if(isset($_POST["save"])){

    $title = trim($_POST["title"]);
    $amount = $_POST["amount"];
    $date = $_POST["income_date"];

    $stmt = $pdo->prepare("
        INSERT INTO incomes(user_id,title,amount,income_date)
        VALUES(?,?,?,?)
    ");

    $stmt->execute([
        $_SESSION["id"],
        $title,
        $amount,
        $date
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Hedefler | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>
<body>
<body>

<?php include "includes/sidebar.php"; ?>
<div class="content">
<h2>Hedefleriniz</h2>
</div>

