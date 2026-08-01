<?php

session_start();

require_once "config/database.php";
require_once "app/controllers/AuthController.php";

$controller = new AuthController($pdo);
$controller->register();