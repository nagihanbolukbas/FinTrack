<?php

class DashboardModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Toplam Gelir
    public function getTotalIncome($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM incomes
            WHERE user_id=?
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Toplam Gider
    public function getTotalExpense($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM expenses
            WHERE user_id=?
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Son İşlemler
    public function getRecentTransactions($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT title,amount,income_date AS tdate,'Gelir' AS type
        FROM incomes
        WHERE user_id=?

        UNION ALL

        SELECT title,amount,expense_date AS tdate,'Gider' AS type
        FROM expenses
        WHERE user_id=?

        ORDER BY tdate DESC
        LIMIT 5
        ");

        $stmt->execute([
            $user_id,
            $user_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Bu Ay Gelir
    public function getThisMonthIncome($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT IFNULL(SUM(amount),0)
        FROM incomes
        WHERE user_id=?
        AND MONTH(income_date)=MONTH(CURDATE())
        AND YEAR(income_date)=YEAR(CURDATE())
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Bu Ay Gider
    public function getThisMonthExpense($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT IFNULL(SUM(amount),0)
        FROM expenses
        WHERE user_id=?
        AND MONTH(expense_date)=MONTH(CURDATE())
        AND YEAR(expense_date)=YEAR(CURDATE())
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // En çok harcanan kategori
    public function getTopCategory($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT category,
               SUM(amount) total
        FROM expenses
        WHERE user_id=?
        GROUP BY category
        ORDER BY total DESC
        LIMIT 1
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hedef Sayısı
    public function getGoalCount($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM goals
            WHERE user_id=?
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Son 30 Gün İşlem Sayısı
    public function getTotalTransactions($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT
        (
            (SELECT COUNT(*) FROM incomes WHERE user_id=?)
            +
            (SELECT COUNT(*) FROM expenses WHERE user_id=?)
        )
        ");

        $stmt->execute([
            $user_id,
            $user_id
        ]);

        return $stmt->fetchColumn();
    }

    // Son 7 Gün Gelir
    public function getLast7DayIncomeCount($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*)
        FROM incomes
        WHERE user_id=?
        AND income_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Son 7 Gün Gider
    public function getLast7DayExpenseCount($user_id)
    {
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*)
        FROM expenses
        WHERE user_id=?
        AND expense_date>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Ortalama günlük harcama
    public function getAverageExpense($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(AVG(amount),0)
            FROM expenses
            WHERE user_id=?
            AND MONTH(expense_date)=MONTH(CURDATE())
            AND YEAR(expense_date)=YEAR(CURDATE())
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Toplam gelir kayıt sayısı
    public function getIncomeCount($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM incomes
            WHERE user_id=?
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Toplam gider kayıt sayısı
    public function getExpenseCount($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM expenses
            WHERE user_id=?
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Geçen ay gider
    public function getLastMonthExpense($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM expenses
            WHERE user_id=?
            AND MONTH(expense_date)=MONTH(DATE_SUB(CURDATE(),INTERVAL 1 MONTH))
            AND YEAR(expense_date)=YEAR(DATE_SUB(CURDATE(),INTERVAL 1 MONTH))
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchColumn();
    }

    // Tamamlanan hedefler
    public function getCompletedGoals($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT title
            FROM goals
            WHERE user_id=?
            AND saved_amount>=target_amount
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // %80'i geçen hedefler
    public function getAlmostCompletedGoals($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT title,
                   (saved_amount/target_amount)*100 AS percent
            FROM goals
            WHERE user_id=?
            AND target_amount>0
            HAVING percent>=80
            AND percent<100
        ");

        $stmt->execute([$user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}