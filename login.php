<?php

session_start();
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $error = "Lütfen tüm alanları doldurun.";

    } else {

        $query = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $query->execute([$email]);

        if ($query->rowCount() == 0) {

            $error = "E-posta veya şifre hatalı.";

        } else {

            $user = $query->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user["password"])) {

    $_SESSION["id"] = $user["id"];
    $_SESSION["first_name"] = $user["first_name"];
    $_SESSION["last_name"] = $user["last_name"];
    $_SESSION["email"] = $user["email"];

    header("Location: dashboard.php");
    exit;

} else {

    $error = "E-posta veya şifre hatalı.";

}

         

        }

    }

}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Giriş Yap | FinTrack</title>

<link rel="stylesheet" href="css/style.css">

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

<div class="auth-page">

    <div class="auth-container">

        <div class="auth-left">

            <h1>Tekrar Hoş Geldin </h1>

            <p>

                Finansını yönetmeye kaldığın yerden devam et.

            </p>

            <ul>

                <li>✔ Gelir & Gider Takibi</li>

                <li>✔ Finansal Analizler</li>

                <li>✔ Bütçe Yönetimi</li>

                <li>✔ Tasarruf Hedefleri</li>

            </ul>

        </div>

        <div class="auth-right">

            <h2>Giriş Yap</h2>

            <?php if($error!=""){ ?>

                <div class="error">

                    <?= $error ?>

                </div>

            <?php } ?>

            <form method="POST">

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

                <button class="auth-btn">

                    Giriş Yap

                </button>

            </form>

            <div class="auth-footer">

                Hesabın yok mu?

                <a href="register.php">

                    Kayıt Ol

                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>