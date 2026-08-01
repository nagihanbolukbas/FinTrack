<?php

require_once "app/models/GoalModel.php";

class DeleteGoalController
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

        $this->goalModel->deleteGoal(
            (int)$_GET["id"],
            $_SESSION["id"]
        );

        header("Location: goals.php");
        exit;
    }
}