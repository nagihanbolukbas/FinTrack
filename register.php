<?php
session_start();
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $error = "Lütfen tüm alanları doldurun.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Geçerli bir e-posta adresi giriniz.";

    } elseif ($password !== $confirm_password) {

        $error = "Şifreler eşleşmiyor.";

    } elseif (strlen($password) < 6) {

        $error = "Şifre en az 6 karakter olmalıdır.";

    } else {

        $query = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $query->execute([$email]);

        if ($query->rowCount() > 0) {

            $error = "Bu e-posta adresi zaten kayıtlı.";

        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $verificationCode = rand(100000,999999);

            $verificationExpiry = date("Y-m-d H:i:s",strtotime("+10 minutes"));

            $insert = $pdo->prepare("
                INSERT INTO users
                (
                    first_name,
                    last_name,
                    email,
                    password,
                    verification_code,
                    verification_expiry,
                    is_verified
                )
                VALUES
                (?,?,?,?,?,?,0)
            ");

            $insert->execute([
                $first_name,
                $last_name,
                $email,
                $passwordHash,
                $verificationCode,
                $verificationExpiry
            ]);

         $_SESSION["success"] = "Hesabınız başarıyla oluşturuldu. Giriş yapabilirsiniz.";

header("Location: login.php");
exit;
        }

    }

}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kayıt Ol | FinTrack</title>

<link rel="stylesheet" href="css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>


<div class="auth-page">

    <div class="auth-container">

        <div class="auth-left">

            <h1>FinTrack</h1>

            <p>

                Finansal geleceğini bugünden planla.

            </p>

            <ul>

                <li>✔ Gelir Takibi</li>

                <li>✔ Gider Yönetimi</li>

                <li>✔ Finansal Raporlar</li>

                <li>✔ Tasarruf Hedefleri</li>

            </ul>

        </div>

        <div class="auth-right">

            <h2>Kayıt Ol</h2>

            <?php if($error!=""){ ?>

                <div class="error">

                    <?= $error ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="input-group">

                    <input
                        type="text"
                        name="first_name"
                        placeholder="Ad"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Soyad"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="email"
                        name="email"
                        placeholder="E-Posta Adresi"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="password"
                        name="password"
                        placeholder="Şifre"
                        required>

                </div>

                <div class="input-group">

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Şifre Tekrar"
                        required>

                </div>

                <button class="auth-btn">

                    Hesap Oluştur

                </button>

            </form>

            <div class="auth-footer">

                Zaten hesabın var mı?

                <a href="login.php">

                    Giriş Yap

                </a>

            </div>

        </div>

    </div>

</div>
</body>


</html>