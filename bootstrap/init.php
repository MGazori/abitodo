<?php
session_start();
include "constants.php";
include BASE_PATH . "bootstrap/config.php";
include BASE_PATH . "libs/helpers.php";

//start and set pdo connection

try {
    $pdo = new PDO("mysql:dbname=$database_config->dbname;host={$database_config->host}", $database_config->user, $database_config->password);
} catch (PDOException $e) {
    diePage('Connection failed: ' . $e->getMessage());
}

include BASE_PATH . "libs/lib-auth.php";
include BASE_PATH . "libs/lib-tasks.php";
