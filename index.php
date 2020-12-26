<?php
include "bootstrap/init.php";
// get all folders
$folders = getFolders();
// get all tasks
$tasks = getTasks();
// var_dump($folders);
include "tpl/index-tpl.php";
