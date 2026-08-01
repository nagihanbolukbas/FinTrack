<?php

class SettingsModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

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

    public function changePassword($userId,$password)
    {
        $stmt=$this->pdo->prepare("
            UPDATE users
            SET password=?
            WHERE id=?
        ");

        $stmt->execute([
            $password,
            $userId
        ]);
    }

    public function deleteAccount($userId)
    {
        $this->pdo->prepare("
            DELETE FROM incomes
            WHERE user_id=?
        ")->execute([$userId]);

        $this->pdo->prepare("
            DELETE FROM expenses
            WHERE user_id=?
        ")->execute([$userId]);

        $this->pdo->prepare("
            DELETE FROM goals
            WHERE user_id=?
        ")->execute([$userId]);

        $this->pdo->prepare("
            DELETE FROM users
            WHERE id=?
        ")->execute([$userId]);
    }

    public function getStatistics($userId)
    {
        $stmt=$this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM incomes
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        $income=$stmt->fetchColumn();

        $stmt=$this->pdo->prepare("
            SELECT IFNULL(SUM(amount),0)
            FROM expenses
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        $expense=$stmt->fetchColumn();

        $stmt=$this->pdo->prepare("
            SELECT COUNT(*)
            FROM goals
            WHERE user_id=?
        ");

        $stmt->execute([$userId]);

        $goals=$stmt->fetchColumn();

        return [
            "income"=>$income,
            "expense"=>$expense,
            "goals"=>$goals,
            "balance"=>$income-$expense
        ];
    }
}