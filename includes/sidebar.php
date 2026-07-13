<div class="sidebar">

    <div class="logo">
        Fin<span>Track</span>
    </div>

    <ul>

        <li class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
                <i class="fa-solid fa-house"></i>
                <span>Anasayfa</span>
            </a>
        </li>
        

        <li class="<?= basename($_SERVER['PHP_SELF'])=='incomes.php' ? 'active' : '' ?>">
            <a href="incomes.php">
                <i class="fa-solid fa-add"></i>
                <span>Gelirler</span>
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF'])=='expenses.php' ? 'active' : '' ?>">
            <a href="expenses.php">
                <i class="fa-solid fa-minus"></i>
                <span>Giderler</span>
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF'])=='reports.php' ? 'active' : '' ?>">
            <a href="reports.php">
                <i class="fa-solid fa-chart-column"></i>
                <span>Raporlar</span>
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF'])=='goals.php' ? 'active' : '' ?>">
            <a href="goals.php">
                <i class="fa-solid fa-bullseye"></i>
                <span>Hedefler</span>
            </a>
        </li>

        <li class="<?= basename($_SERVER['PHP_SELF'])=='profil.php' ? 'active' : '' ?>">
            <a href="profil.php">
                <i class="fa-solid fa-user"></i>
                <span>Profil</span>
            </a>
        </li>
        <li class="<?= basename($_SERVER['PHP_SELF'])=='ayarlar.php' ? 'active' : '' ?>">
            <a href="settings.php">
                <i class="fa-solid fa-wrench"></i>
                <span>Ayarlar</span>
            </a>
        </li>

    </ul>

</div>