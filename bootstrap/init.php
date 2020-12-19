<?php

include "constants.php";
include "config.php";
include "libs/helpers.php";

//start and set pdo connection

try {
    $pdo = new PDO("mysql:dbname=$database_config->dbname;host={$database_config->host}", $database_config->user, $database_config->password);
} catch (PDOException $e) {
    diePage('Connection failed: ' . $e->getMessage());
}

// echo "connection successfully";
