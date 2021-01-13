<?php
include "bootstrap/init.php";
//user init
if (isset($_GET['logout'])) {
    logout();
}
if (!isLoggedIn()) {
    redirect(site_url("auth.php"));
}
//get login user info
$userInfo = getLoggedInUserInfo($_SESSION['login']);
$userInfo->profileImage = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email)));;
// get all folders
$folders = getFolders();
// get all tasks
$tasks = getTasks();
//for showing empty task
if (!sizeof($tasks)) {
    $emptyTask = "<div class='emptyTask'>No Task Here!</div>";
}
// var_dump($folders);
include "tpl/index-tpl.php";
