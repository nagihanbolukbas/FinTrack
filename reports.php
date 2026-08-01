

<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/ReportController.php";

$controller = new ReportController($pdo);
$controller->index();