<?php

class IncomeModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Hedefleri getir
    public function getGoalList($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, title
            FROM goals
            WHERE user_id=?
            ORDER BY title
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Gelir kaydet
    public function saveIncome($userId,$category,$amount,$date)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO incomes
            (user_id,title,amount,income_date,category,description)
            VALUES (?,?,?,?,?,?)
        ");

        $stmt->execute([
            $userId,
            $category,
            $amount,
            $date,
            $category,
            null
        ]);
    }

    // Hedefe para ekle
    public function addMoneyToGoal($userId,$goalId,$amount)
    {
        $stmt = $this->pdo->prepare("
            UPDATE goals
            SET saved_amount=saved_amount+?
            WHERE id=? AND user_id=?
        ");

        $stmt->execute([
            $amount,
            $goalId,
            $userId
        ]);
    }

    // Son gelirler
    public function getRecentIncomes($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM incomes
            WHERE user_id=?
            ORDER BY id DESC
            LIMIT 10
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Tek gelir getir
public function getIncomeById($id,$userId)
{
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM incomes
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([$id,$userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Güncelle
public function updateIncome($id,$userId,$category,$amount,$date)
{
    $stmt = $this->pdo->prepare("
        UPDATE incomes
        SET
            title=?,
            category=?,
            amount=?,
            income_date=?
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([
        $category,
        $category,
        $amount,
        $date,
        $id,
        $userId
    ]);
}
public function deleteIncome($id,$userId)
{
    $stmt = $this->pdo->prepare("
        DELETE FROM incomes
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([
        $id,
        $userId
    ]);
}
}