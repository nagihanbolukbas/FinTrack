<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/ReportPdfController.php";

$controller = new ReportPdfController($pdo);
$controller->index();