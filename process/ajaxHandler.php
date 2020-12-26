<?php
include_once "../bootstrap/init.php";

if (!isAjaxRequest()) {
    diePage("Invalid Request!");
}

if (!isset($_POST['action']) || empty($_POST['action'])) {
    diePage("Invalid Action!");
}
switch ($_POST['action']) {
    case "addFolder":
        if (!isset($_POST['folderName']) || strlen($_POST['folderName']) < 3) {
            $addFolderError = [
                "name" => "addFolderError",
                "description" => "The folder name must be at least 3 letters long."
            ];
            echo json_encode($addFolderError);
        } else {
            $lasInsertId = addFolder($_POST['folderName']);
            echo json_encode(addNewFolderRow($lasInsertId));
        }
        break;
    default:
        diePage("Invalid Action!");
}
