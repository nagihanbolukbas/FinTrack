<?php

session_start();

if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit;
}

require_once "config/database.php";


$user_id = $_SESSION["id"];

$message = "";
$error = "";


// Kullanıcı bilgilerini getir

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);




// Şifre değiştirme

if(isset($_POST["change_password"])){

    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $again_password = $_POST["again_password"];


    if(!password_verify($old_password,$user["password"])){

        $error="Mevcut şifre yanlış.";

    }elseif($new_password != $again_password){

        $error="Yeni şifreler uyuşmuyor.";

    }else{


        $hashed_password=password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );


        $stmt=$pdo->prepare("
        UPDATE users
        SET password=?
        WHERE id=?
        ");


        $stmt->execute([
            $hashed_password,
            $user_id
        ]);


        $message="Şifreniz başarıyla değiştirildi.";

    }

}




// Hesap silme

if(isset($_POST["delete_account"])){

    $delete_password=$_POST["delete_password"];


    if(password_verify($delete_password,$user["password"])){


        // Kullanıcı verilerini temizle

        $pdo->prepare("
        DELETE FROM incomes
        WHERE user_id=?
        ")->execute([$user_id]);



        $pdo->prepare("
        DELETE FROM expenses
        WHERE user_id=?
        ")->execute([$user_id]);



        $pdo->prepare("
        DELETE FROM goals
        WHERE user_id=?
        ")->execute([$user_id]);



        $pdo->prepare("
        DELETE FROM users
        WHERE id=?
        ")->execute([$user_id]);



        session_destroy();


        header("Location: login.php");
        exit;


    }else{

        $error="Hesap silme şifresi yanlış.";

    }

}


?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ayarlar | FinTrack</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>
<body>


<?php include "includes/sidebar.php"; ?>
<div class="content">
<div class="settings-header">

    <div>
        <h2>⚙️ Hesap Ayarları</h2>
        <p>FinTrack hesabınızı ve uygulama tercihlerinizi yönetin.</p>
    </div>

</div>



<?php if($message){ ?>

<p class="success">
<?= $message ?>
</p>

<?php } ?>


<?php if($error){ ?>

<p class="error">
<?= $error ?>
</p>

<?php } ?>




<div class="settings-card">

<h3>
<i class="fa-solid fa-user"></i>
Profil Bilgileri
</h3>

<div class="info-item">
    <span>Ad Soyad</span>
    <strong>
        <?= htmlspecialchars($user["first_name"]." ".$user["last_name"]) ?>
    </strong>
</div>

<div class="info-item">
    <span>E-Posta</span>
    <strong>
        <?= htmlspecialchars($user["email"]) ?>
    </strong>
</div>

</div>
<?php


$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0)
FROM incomes
WHERE user_id=?
");

$stmt->execute([$user_id]);
$totalIncome = $stmt->fetchColumn();

$stmt = $pdo->prepare("
SELECT IFNULL(SUM(amount),0)
FROM expenses
WHERE user_id=?
");

$stmt->execute([$user_id]);
$totalExpense = $stmt->fetchColumn();

$stmt = $pdo->prepare("
SELECT COUNT(*)
FROM goals
WHERE user_id=?
");

$stmt->execute([$user_id]);
$goalCount = $stmt->fetchColumn();

$balance = $totalIncome - $totalExpense;

?>









<div class="settings-card">


<h3>🔐 Şifre Değiştir</h3>


<br>


<form method="POST" class="income-form">


<input
type="password"
name="old_password"
placeholder="Mevcut şifre"
required>



<input
type="password"
name="new_password"
placeholder="Yeni şifre"
required>



<input
type="password"
name="again_password"
placeholder="Yeni şifre tekrar"
required>



<button 
class="auth-btn"
name="change_password">

Şifreyi Güncelle

</button>


</form>


</div>








<div class="settings-card danger-card">


<h3 style="color:red;">
⚠️ Hesabı Sil
</h3>


<p>
Hesabınızı sildiğinizde tüm gelir, gider ve hedef kayıtlarınız silinir.
</p>


<br>


<form method="POST" class="income-form">


<input
type="password"
name="delete_password"
placeholder="Şifrenizi girin"
required>


<button 
class="auth-btn"
name="delete_account"
onclick="return confirm('Hesabınızı silmek istediğinize emin misiniz?')">

Hesabı Sil

</button>


</form>


</div>





</div>



</body>

</html>