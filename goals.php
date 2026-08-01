

<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/GoalController.php";

$controller = new GoalController($pdo);
$controller->index();