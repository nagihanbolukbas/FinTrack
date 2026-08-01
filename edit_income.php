<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/EditIncomeController.php";

$controller = new EditIncomeController($pdo);
$controller->index(); 