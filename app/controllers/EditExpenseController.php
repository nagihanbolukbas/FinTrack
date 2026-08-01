<?php

require_once "app/models/ExpenseModel.php";

class EditExpenseController
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

        if(!isset($_GET["id"])){
            header("Location: expenses.php");
            exit;
        }

        $userId = $_SESSION["id"];
        $id = (int)$_GET["id"];

        $expenseModel = new ExpenseModel($this->pdo);

        $expense = $expenseModel->getExpenseById($id,$userId);

        if(!$expense){
            die("Kayıt bulunamadı.");
        }

        if(isset($_POST["save"])){

            $expenseModel->updateExpense(
                $id,
                $userId,
                $_POST["category"],
                $_POST["amount"],
                $_POST["expense_date"]
            );

            header("Location: expenses.php");
            exit;
        }

        require "app/views/expenses/edit.php";
    }
}