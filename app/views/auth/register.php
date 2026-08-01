<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kayıt Ol | FinTrack</title>

<link rel="stylesheet" href="assets/css/style.css">

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

<small class="password-info">
    Şifre en az <strong>6 karakter</strong> olmalıdır.
</small>

             

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