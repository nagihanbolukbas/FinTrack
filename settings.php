<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/SettingsController.php";

$controller = new SettingsController($pdo);
$controller->index();