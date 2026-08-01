<?php

require_once "app/models/DashboardModel.php";

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        if (!isset($_SESSION["id"])) {

            header("Location: login.php");
            exit;
        }

        $user_id = $_SESSION["id"];
        $userName = $_SESSION["first_name"];

        $dashboard = new DashboardModel($this->pdo);

        // Toplamlar
        $totalIncome = $dashboard->getTotalIncome($user_id);
        $totalExpense = $dashboard->getTotalExpense($user_id);

        $balance = $totalIncome - $totalExpense;

        // Son işlemler
        $transactions = $dashboard->getRecentTransactions($user_id);

        // Bu ay
        $thisMonthIncome = $dashboard->getThisMonthIncome($user_id);
        $thisMonthExpense = $dashboard->getThisMonthExpense($user_id);

        $thisMonthBalance = $thisMonthIncome - $thisMonthExpense;

        // Hedefler
        $goalCount = $dashboard->getGoalCount($user_id);

        // Kategori
        $topCategory = $dashboard->getTopCategory($user_id);

        // İstatistikler
        $totalTransactions = $dashboard->getTotalTransactions($user_id);

        $incomeCount7 = $dashboard->getLast7DayIncomeCount($user_id);
        $expenseCount7 = $dashboard->getLast7DayExpenseCount($user_id);

        $avgExpense = $dashboard->getAverageExpense($user_id);

        $incomeCount = $dashboard->getIncomeCount($user_id);
        $expenseCount = $dashboard->getExpenseCount($user_id);

        $lastMonthExpense = $dashboard->getLastMonthExpense($user_id);

        $completedGoals = $dashboard->getCompletedGoals($user_id);

        $almostCompletedGoals = $dashboard->getAlmostCompletedGoals($user_id);

        /* Döviz */

        require_once "app/helpers/CurrencyHelper.php";

$currency = CurrencyHelper::getRates();

        $usdRate = $currency["rates"]["USD"];
        $eurRate = $currency["rates"]["EUR"];

        $incomeUSD = $totalIncome * $usdRate;
        $incomeEUR = $totalIncome * $eurRate;

        $expenseUSD = $totalExpense * $usdRate;
        $expenseEUR = $totalExpense * $eurRate;

        $balanceUSD = $balance * $usdRate;
        $balanceEUR = $balance * $eurRate;

        $rateDate = $currency["date"];

        $notifications = [];
                // Negatif bakiye
        if ($balance < 0) {
            $notifications[] = "⚠️ Bakiyeniz negatife düştü.";
        }

        // Bu ay gider fazla
        if ($thisMonthExpense > $thisMonthIncome) {
            $notifications[] = "🔴 Bu ay giderleriniz gelirinizden fazla.";
        }

        // Hedef yok
        if ($goalCount == 0) {
            $notifications[] = "🎯 Henüz tasarruf hedefi oluşturmadınız.";
        }

        // En çok harcanan kategori
        if ($topCategory) {

            $percent = 0;

            if ($totalExpense > 0) {
                $percent = ($topCategory["total"] / $totalExpense) * 100;
            }

            $notifications[] =
                "📊 En çok harcama kategoriniz: " . $topCategory["category"];

            if ($percent >= 40) {
                $notifications[] =
                    "⚠️ Harcamalarınızın %" . round($percent) .
                    " kadarı '" . $topCategory["category"] . "' kategorisinde.";
            }
        }

        // Son 7 gün gelir
        if ($incomeCount7 == 0) {
            $notifications[] = "💰 Son 7 gündür gelir eklemediniz.";
        }

        // Son 7 gün gider
        if ($expenseCount7 == 0) {
            $notifications[] = "🧾 Son 7 gündür gider eklemediniz.";
        }

        // Tamamlanan hedefler
        foreach ($completedGoals as $goal) {

            $notifications[] =
                "🏆 '" . $goal["title"] . "' hedefiniz tamamlandı.";
        }

        // %80 geçen hedefler
        foreach ($almostCompletedGoals as $goal) {

            $notifications[] =
                "🎯 '" . $goal["title"] . "' hedefiniz %80 seviyesine ulaştı.";
        }

        // Toplam işlem sayısı
        if ($totalTransactions >= 30) {

            $notifications[] =
                "🏆 Tebrikler! " .
                $totalTransactions .
                " finansal işlem gerçekleştirdiniz.";
        }

        // Ortalama harcama
        if ($avgExpense > 0) {

            $notifications[] =
                "💸 Ortalama harcamanız ₺" .
                number_format($avgExpense, 2, ",", ".");
        }

        // İlk gelir
        if ($incomeCount == 0) {

            $notifications[] =
                "💰 İlk gelir kaydınızı ekleyerek finans takibine başlayabilirsiniz.";
        }

        // İlk gider
        if ($expenseCount == 0) {

            $notifications[] =
                "🧾 İlk gider kaydınızı ekleyerek harcamalarınızı takip edebilirsiniz.";
        }

        // Geçen aya göre fazla harcama
        if (
            $lastMonthExpense > 0 &&
            $thisMonthExpense > $lastMonthExpense
        ) {

            $notifications[] =
                "📈 Bu ay geçen aya göre daha fazla harcama yaptınız.";
        }

        // Tasarruf
        if (
            $thisMonthIncome > $thisMonthExpense &&
            $balance >= 0
        ) {

            $notifications[] =
                "✅ Tebrikler! Bu ay tasarruf yapmayı başardınız.";
        }
                require "app/views/dashboard/index.php";
    }
}