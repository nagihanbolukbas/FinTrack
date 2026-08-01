<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/DeleteGoalController.php";

$controller = new DeleteGoalController($pdo);
$controller->index();