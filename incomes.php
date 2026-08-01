<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/IncomeController.php";

$controller = new IncomeController($pdo);
$controller->index();