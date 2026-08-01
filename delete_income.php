<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/DeleteIncomeController.php";

$controller = new DeleteIncomeController($pdo);
$controller->index();