<?php

require_once "app/models/GoalModel.php";

class EditGoalController
{
    private $goalModel;

    public function __construct($pdo)
    {
        $this->goalModel = new GoalModel($pdo);
    }

    public function index()
    {
        if(!isset($_SESSION["id"])){
            header("Location: login.php");
            exit;
        }

        if(!isset($_GET["id"])){
            header("Location: goals.php");
            exit;
        }

        $id = (int)$_GET["id"];
        $userId = $_SESSION["id"];

        $goal = $this->goalModel->getGoal($id,$userId);

        if(!$goal){
            die("Kayıt bulunamadı.");
        }

        if(isset($_POST["save"])){

           $this->goalModel->updateGoal(
    $id,
    $userId,
    $_POST["title"],
    $_POST["category"],
    $_POST["target_amount"],
    $_POST["saved_amount"],
    $_POST["deadline"]
);

            header("Location: goals.php");
            exit;
        }

        require "app/views/goals/edit.php";
    }
}