<?php

require_once "app/models/IncomeModel.php";

class IncomeController
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

        $income = new IncomeModel($this->pdo);

        // Hedefler
        $goalList = $income->getGoalList($userId);

        // Kayıt işlemi
        if(isset($_POST["save"])){

            if($_POST["category"] == "Hedef"){

                $income->addMoneyToGoal(
                    $userId,
                    $_POST["goal_id"],
                    $_POST["amount"]
                );

            }else{

                $income->saveIncome(
                    $userId,
                    $_POST["category"],
                    $_POST["amount"],
                    $_POST["income_date"]
                );

            }

            header("Location: incomes.php");
            exit;
        }

        // Son gelirler
        $recent_incomes = $income->getRecentIncomes($userId);

        require "app/views/incomes/index.php";
    }
}