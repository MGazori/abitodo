<?php
include "bootstrap/init.php";
//user init
if (isset($_GET['logout'])) {
    logout();
    redirect(site_url("auth.php"));
}
if (!isLoggedIn()) {
    redirect(site_url("auth.php"));
}
if (is_null(getUserIdByToken($_COOKIE['SULGI'])) || getUserIdByToken($_COOKIE['SULGI']) == false) {
    logout();
    redirect(site_url("auth.php"));
}
//get user id by cookie
$userIdByToken = getUserIdByToken($_COOKIE['SULGI'])->user_id;
//get login user info
$userInfo = getLoggedInUserInfo($userIdByToken);
$userInfo->profileImage = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($userInfo->email)));;
// get all folders
$folders = getFolders();
// get all tasks
$tasks = getTasks();
//for showing empty task
if (!sizeof($tasks)) {
    $emptyTask = "<div class='emptyTask'>No Task Here!</div>";
}
include "tpl/index-tpl.php";
