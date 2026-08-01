<?php

class ProfilModel
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
            SELECT *
            FROM users
            WHERE id=?
        ");

        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Toplam Gelir
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

    // Toplam Gider
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

    // Hedef Sayısı
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
}