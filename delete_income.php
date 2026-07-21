<?php

session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET["id"] ?? 0;

$stmt = $pdo->prepare("
DELETE FROM incomes
WHERE id=? AND user_id=?
");

$stmt->execute([
    $id,
    $_SESSION["id"]
]);

header("Location: incomes.php");
exit;