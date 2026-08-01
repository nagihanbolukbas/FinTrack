<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/EditGoalController.php";

$controller = new EditGoalController($pdo);
$controller->index();