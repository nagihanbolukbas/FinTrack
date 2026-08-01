<?php

require_once "app/models/ReportModel.php";

class ReportController
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
        $month = $_GET["month"] ?? "";

        $report = new ReportModel($this->pdo);

        // Toplamlar
        $totalIncome = $report->getTotalIncome($userId,$month);
        $totalExpense = $report->getTotalExpense($userId,$month);
        $balance = $totalIncome - $totalExpense;

        // Listeler
        $incomeList = $report->getIncomeList($userId,$month);
        $expenseList = $report->getExpenseList($userId,$month);

        $incomeSummary = $report->getIncomeSummary($userId,$month);
        $expenseSummary = $report->getExpenseSummary($userId,$month);
        
        // En çok harcanan kategori
        $topCategory = $report->getTopCategory($userId);
        $categoryName = $topCategory["category"] ?? "-";

        // Tasarruf oranı
        $savingRate = 0;

        if($totalIncome > 0){
            $savingRate = ($balance / $totalIncome) * 100;
        }

        // Finans skoru
        $score = 50;

        if($balance > 0) $score += 20;
        if($savingRate > 20) $score += 20;
        if($savingRate > 40) $score += 10;

        if($score > 100){
            $score = 100;
        }

        require "app/views/reports/index.php";
    }
}