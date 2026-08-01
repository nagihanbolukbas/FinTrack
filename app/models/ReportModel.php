<?php

class ReportModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Toplam Gelir
    public function getTotalIncome($userId, $month = "")
    {
        $params = [$userId];

        $sql = "
        SELECT IFNULL(SUM(amount),0)
        FROM incomes
        WHERE user_id=?
        ";

        if($month != ""){
            $sql .= " AND MONTH(income_date)=?";
            $params[] = $month;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

    // Toplam Gider
    public function getTotalExpense($userId, $month = "")
    {
        $params = [$userId];

        $sql = "
        SELECT IFNULL(SUM(amount),0)
        FROM expenses
        WHERE user_id=?
        ";

        if($month != ""){
            $sql .= " AND MONTH(expense_date)=?";
            $params[] = $month;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn();
    }

public function getIncomeList($userId,$month="")
{
    $params=[$userId];

    $sql="
    SELECT title,category,amount,income_date
    FROM incomes
    WHERE user_id=?
    ";

    if($month!=""){
        $sql.=" AND MONTH(income_date)=?";
        $params[]=$month;
    }

    $sql.=" ORDER BY income_date DESC";

    $stmt=$this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getExpenseList($userId,$month="")
{
    $params=[$userId];

    $sql="
    SELECT category,amount,expense_date
    FROM expenses
    WHERE user_id=?
    ";

    if($month!=""){
        $sql.=" AND MONTH(expense_date)=?";
        $params[]=$month;
    }

    $sql.=" ORDER BY expense_date DESC";

    $stmt=$this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getTopCategory($userId)
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

    $stmt->execute([$userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getIncomeSummary($userId,$month="")
{
    $params = [$userId];

    $sql = "
    SELECT category,
           SUM(amount) total
    FROM incomes
    WHERE user_id=?
    ";

    if($month!=""){
        $sql .= " AND MONTH(income_date)=?";
        $params[] = $month;
    }

    $sql .= " GROUP BY category";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getExpenseSummary($userId,$month="")
{
    $params = [$userId];

    $sql = "
    SELECT category,
           SUM(amount) total
    FROM expenses
    WHERE user_id=?
    ";

    if($month!=""){
        $sql .= " AND MONTH(expense_date)=?";
        $params[] = $month;
    }

    $sql .= " GROUP BY category";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
