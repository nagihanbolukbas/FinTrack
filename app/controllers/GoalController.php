<?php

require_once "app/models/GoalModel.php";

class GoalController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        if (!isset($_SESSION["id"])) {
            header("Location: login.php");
            exit;
        }

        $userId = $_SESSION["id"];

        $goal = new GoalModel($this->pdo);

        /* Hedef Ekle */

        if (isset($_POST["save"])) {

            $goal->addGoal(
                $userId,
                trim($_POST["title"]),
                $_POST["category"],
                $_POST["target_amount"],
                $_POST["saved_amount"],
                $_POST["deadline"]
            );

            header("Location: goals.php");
            exit;
        }

        /* Para Ekle */

        if (isset($_POST["add_money"])) {

            $goal->addMoney(
                $userId,
                $_POST["goal_id"],
                $_POST["amount"]
            );

            header("Location: goals.php");
            exit;
        }

        /* Para Çıkar */

        if (isset($_POST["remove_money"])) {

            $goal->removeMoney(
                $userId,
                $_POST["goal_id"],
                $_POST["amount"]
            );

            header("Location: goals.php");
            exit;
        }

        // Hedefleri getir
        $goals = $goal->getGoals($userId);

        // İstatistikler
        $totalGoals = count($goals);

        $completedGoals = 0;
        $nearestGoal = null;
        $highestPercent = 0;

        foreach ($goals as $item) {

            if ($item["target_amount"] > 0) {
                $percent = ($item["saved_amount"] / $item["target_amount"]) * 100;
            } else {
                $percent = 0;
            }

            if ($percent >= 100) {
                $completedGoals++;
            }

            if ($percent > $highestPercent && $percent < 100) {
                $highestPercent = $percent;
                $nearestGoal = $item;
            }
        }

        require "app/views/goals/index.php";
    }
    public function edit($id)
{
    if(!isset($_SESSION["id"])){
        header("Location: login.php");
        exit;
    }

    $goalModel = new GoalModel($this->pdo);

    $goal = $goalModel->find($id, $_SESSION["id"]);

    if(!$goal){
        die("Kayıt bulunamadı.");
    }

    if(isset($_POST["save"])){

        $goalModel->update(
            $id,
            $_SESSION["id"],
            $_POST["title"],
            $_POST["target_amount"],
            $_POST["saved_amount"]
        );

        header("Location: goals.php");
        exit;
    }

    require "app/views/goals/edit.php";
}
}