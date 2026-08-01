<?php

require_once "app/models/IncomeModel.php";

class DeleteIncomeController
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

        $id = $_GET["id"] ?? 0;

        $incomeModel = new IncomeModel($this->pdo);

        $incomeModel->deleteIncome(
            $id,
            $_SESSION["id"]
        );

        header("Location: incomes.php");
        exit;
    }
}