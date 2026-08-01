<?php

class GoalModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Hedef Ekle
    public function addGoal($userId, $title, $category, $target, $saved, $deadline)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO goals
            (user_id,title,category,target_amount,saved_amount,deadline)
            VALUES(?,?,?,?,?,?)
        ");

        return $stmt->execute([
            $userId,
            $title,
            $category,
            $target,
            $saved,
            $deadline
        ]);
    }

    // Hedefe Para Ekle
    public function addMoney($userId, $goalId, $amount)
    {
        $stmt = $this->pdo->prepare("
            UPDATE goals
            SET saved_amount = saved_amount + ?
            WHERE id=? AND user_id=?
        ");

        return $stmt->execute([
            $amount,
            $goalId,
            $userId
        ]);
    }

    // Hedeften Para Çıkar
    public function removeMoney($userId, $goalId, $amount)
    {
        $stmt = $this->pdo->prepare("
            UPDATE goals
            SET saved_amount =
            CASE
                WHEN saved_amount-? < 0 THEN 0
                ELSE saved_amount-?
            END
            WHERE id=? AND user_id=?
        ");

        return $stmt->execute([
            $amount,
            $amount,
            $goalId,
            $userId
        ]);
    }

    // Tüm Hedefler
    public function getGoals($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM goals
            WHERE user_id=?
            ORDER BY id DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getGoal($id,$userId)
{
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM goals
        WHERE id=? AND user_id=?
    ");

    $stmt->execute([$id,$userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateGoal($id,$userId,$title,$category,$target,$saved,$deadline)
{
    $stmt = $this->pdo->prepare("
        UPDATE goals
        SET
            title=?,
            category=?,
            target_amount=?,
            saved_amount=?,
            deadline=?
        WHERE id=? AND user_id=?
    ");

    return $stmt->execute([
        $title,
        $category,
        $target,
        $saved,
        $deadline,
        $id,
        $userId
    ]);
}

  

public function deleteGoal($id,$userId)
{
    $stmt = $this->pdo->prepare("
        DELETE FROM goals
        WHERE id=? AND user_id=?
    ");

    return $stmt->execute([
        $id,
        $userId
    ]);
}
}