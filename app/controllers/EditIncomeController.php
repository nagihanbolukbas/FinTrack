<?php

require_once "app/models/IncomeModel.php";

class EditIncomeController
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
            header("Location: incomes.php");
            exit;
        }

        $userId = $_SESSION["id"];
        $id = (int)$_GET["id"];

        $incomeModel = new IncomeModel($this->pdo);

        $income = $incomeModel->getIncomeById($id,$userId);

        if(!$income){
            die("Kayıt bulunamadı.");
        }

        if(isset($_POST["save"])){

            $incomeModel->updateIncome(
                $id,
                $userId,
                $_POST["category"],
                $_POST["amount"],
                $_POST["income_date"]
            );

            header("Location: incomes.php");
            exit;
        }

        require "app/views/incomes/edit.php";
    }
}