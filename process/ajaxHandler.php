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
    case "deleteFolder":
        if (!isset($_POST['deleteFolderId'])) {
            $deleteFolderError = [
                "name" => "deleteFolderError",
                "description" => "Folder not deleted!"
            ];
            echo json_encode($deleteFolderError);
        } else {
            echo json_encode(deleteFolders($_POST['deleteFolderId']));
        }
        break;
    case "selectFolder":
        if (!isset($_POST['folderSelectedId'])) {
            $deleteFolderError = [
                "name" => "selectFolderError",
                "description" => "Folder not deleted!"
            ];
            echo json_encode($deleteFolderError);
        } else {
            filterTasksByFolder($_POST['folderSelectedId']);
        }
        break;
    case "deleteTask":
        if (!isset($_POST['deleteTaskId'])) {
            $deleteTaskError = [
                "name" => "deleteTaskError",
                "description" => "Task not deleted!"
            ];
            echo json_encode($deleteTaskError);
        } else {
            echo json_encode(deleteTasks($_POST['deleteTaskId']));
        }
        break;
    default:
        diePage("Invalid Action!");
}
