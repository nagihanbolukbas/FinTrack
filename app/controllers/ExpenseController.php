<?php

require_once "app/models/ExpenseModel.php";

class ExpenseController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        if(!isset($_SESSION["id"])){
            header("Location: login.php");
            exit;
        }

        $userId = $_SESSION["id"];

        $expense = new ExpenseModel($this->pdo);

        if(isset($_POST["save"])){

            $expense->saveExpense(
                $userId,
                $_POST["category"],
                $_POST["amount"],
                $_POST["expense_date"]
            );

            header("Location: expenses.php");
            exit;
        }

        $recent_expenses = $expense->getRecentExpenses($userId);

        require "app/views/expenses/index.php";
    }
    public function delete($id)
{
    if(!isset($_SESSION["id"])){
        header("Location: login.php");
        exit;
    }

    $expense = new ExpenseModel($this->pdo);

    $expense->delete($id,$_SESSION["id"]);

    header("Location: expenses.php");
    exit;
}
}