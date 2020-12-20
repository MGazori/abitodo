<?php
include "bootstrap/init.php";
//check delete_foder get method request
if (isset($_GET['delete_folder']) && is_numeric($_GET['delete_folder'])) {
    $deleteCount = deleteFolders($_GET['delete_folder']);
    // echo "$deleteCount folders deleted";
}
$folders = getFolders();
// var_dump($folders);
include "tpl/index-tpl.php";
