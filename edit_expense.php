


<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/EditExpenseController.php";

$controller = new EditExpenseController($pdo);
$controller->index();