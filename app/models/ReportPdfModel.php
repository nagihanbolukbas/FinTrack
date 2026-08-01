<?php

class ReportPdfModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Kullanıcı bilgileri
    public function getUser($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT first_name,last_name,email
            FROM users
            WHERE id=?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Toplam gelir
    public function getTotalIncome($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM incomes
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchColumn();
    }

    // Toplam gider
    public function getTotalExpense($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM expenses
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchColumn();
    }

    // Hedef sayısı
    public function getGoalCount($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM goals
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchColumn();
    }

    // Son 10 işlem
    public function getTransactions($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                title,
                amount,
                income_date AS tdate,
                'Gelir' AS type
            FROM incomes
            WHERE user_id=?

            UNION ALL

            SELECT
                title,
                amount,
                expense_date AS tdate,
                'Gider' AS type
            FROM expenses
            WHERE user_id=?

            ORDER BY tdate DESC
            LIMIT 10
        ");

        $stmt->execute([
            $userId,
            $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}