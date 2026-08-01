<?php

session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET["id"] ?? 0;

$stmt = $pdo->prepare("
DELETE FROM expenses
WHERE id=? AND user_id=?
");

$stmt->execute([
    $id,
    $_SESSION["id"]
]);

header("Location: expenses.php");
exit;