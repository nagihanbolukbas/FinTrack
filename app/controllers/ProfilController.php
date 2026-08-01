<?php

require_once "app/models/ProfilModel.php";

class ProfilController
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

        $profil = new ProfilModel($this->pdo);

        // Kullanıcı
        $user = $profil->getUser($userId);

        // Finans Verileri
        $totalIncome = $profil->getTotalIncome($userId);

        $totalExpense = $profil->getTotalExpense($userId);

        $goalCount = $profil->getGoalCount($userId);

        $balance = $totalIncome - $totalExpense;

        // Finans Skoru
        $score = 100;

        if($balance < 0){
            $score -= 40;
        }

        if($totalExpense > $totalIncome){
            $score -= 25;
        }

        if($goalCount == 0){
            $score -= 10;
        }

        if($score < 0){
            $score = 0;
        }

        // Durum
        if($score >= 85){

            $status = "Mükemmel";
            $statusColor = "#16A34A";

        }elseif($score >= 70){

            $status = "İyi";
            $statusColor = "#22C55E";

        }elseif($score >= 50){

            $status = "Orta";
            $statusColor = "#F59E0B";

        }else{

            $status = "Riskli";
            $statusColor = "#DC2626";

        }

        require "app/views/profil/index.php";
    }
}