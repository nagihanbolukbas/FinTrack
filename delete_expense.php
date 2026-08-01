

<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/DeleteExpenseController.php";

$controller = new DeleteExpenseController($pdo);
$controller->index();