

<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/DashboardController.php";

$controller = new DashboardController($pdo);
$controller->index();