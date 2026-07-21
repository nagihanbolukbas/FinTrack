<?php
session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id=$_GET["id"];

$stmt=$pdo->prepare("
SELECT *
FROM goals
WHERE id=? AND user_id=?
");

$stmt->execute([$id,$_SESSION["id"]]);

$goal=$stmt->fetch();

if(!$goal){
    die("Kayıt bulunamadı.");
}

if(isset($_POST["save"])){

    $stmt=$pdo->prepare("
    UPDATE goals
    SET
    title=?,
    target_amount=?,
    saved_amount=?
    WHERE id=? AND user_id=?
    ");

    $stmt->execute([
        $_POST["title"],
        $_POST["target_amount"],
        $_POST["saved_amount"],
        $id,
        $_SESSION["id"]
    ]);

    header("Location: goals.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<title>Hedef Düzenle</title>

<link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<?php include "includes/sidebar.php"; ?>

<div class="content">

<h2>🎯 Hedef Düzenle</h2>

<br>

<form method="POST" class="income-form">

<input
type="text"
name="title"
value="<?= htmlspecialchars($goal["title"]) ?>"
required>

<input
type="number"
step="0.01"
name="target_amount"
value="<?= $goal["target_amount"] ?>"
required>

<input
type="number"
step="0.01"
name="saved_amount"
value="<?= $goal["saved_amount"] ?>"
required>

<button class="auth-btn" name="save">

Kaydet

</button>

</form>

</div>

</body>

</html>