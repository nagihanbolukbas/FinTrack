<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/ExpenseController.php";

$controller = new ExpenseController($pdo);
$controller->index();