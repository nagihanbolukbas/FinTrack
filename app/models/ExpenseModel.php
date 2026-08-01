<?php

class ExpenseModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Gider Kaydet
    public function saveExpense($userId,$category,$amount,$date)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO expenses
            (user_id,title,amount,expense_date,category)
            VALUES(?,?,?,?,?)
        ");

        $stmt->execute([
            $userId,
            $category,
            $amount,
            $date,
            $category
        ]);
    }

    // Son Giderler
    public function getRecentExpenses($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM expenses
            WHERE user_id=?
            ORDER BY id DESC
            LIMIT 10
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getExpenseById($id,$userId)
{
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM expenses
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([$id,$userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateExpense($id,$userId,$category,$amount,$date)
{
    $stmt = $this->pdo->prepare("
        UPDATE expenses
        SET
            title=?,
            category=?,
            amount=?,
            expense_date=?
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
public function delete($id,$userId)
{
    $stmt = $this->pdo->prepare("
        DELETE FROM expenses
        WHERE id=? AND user_id=?
    ");

    return $stmt->execute([
        $id,
        $userId
    ]);
}
}