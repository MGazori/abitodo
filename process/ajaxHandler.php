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
            die();
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
            die();
        } else {
            echo json_encode(deleteFolders($_POST['deleteFolderId']));
        }
        break;
    case "selectFolder":
        if (!isset($_POST['folderSelectedId'])) {
            $selectFolderError = [
                "name" => "selectFolderError",
                "description" => "Folder not deleted!"
            ];
            echo json_encode($selectFolderError);
            die();
        } else {
            filterTasksByFolder($_POST['folderSelectedId']);
        }
        break;
    case "addTask":
        if (!isset($_POST['tasksFolder']) || $_POST['tasksFolder'] == "all") {
            $addTaskError = [
                "name" => "addTaskError",
                "description" => "Folder Not Selected!"
            ];
            echo json_encode($addTaskError);
            die();
        } else if (strlen($_POST['taskTitle']) < 3 || empty($_POST['taskTitle'])) {
            $addTaskError = [
                "name" => "addTaskError",
                "description" => "The task name must be at least 3 letters long."
            ];
            echo json_encode($addTaskError);
            die();
        } else {
            $addTask = addTask($_POST['taskTitle'], $_POST['tasksFolder']);
            $taskInfo = getSingleTask($addTask);
            echo json_encode($taskInfo);
        }
        break;
    case "deleteTask":
        if (!isset($_POST['deleteTaskId'])) {
            $deleteTaskError = [
                "name" => "deleteTaskError",
                "description" => "Task not deleted!"
            ];
            echo json_encode($deleteTaskError);
            die();
        } else {
            echo json_encode(deleteTasks($_POST['deleteTaskId']));
        }
        break;
    case "taskDoneSwitch":
        if (!isset($_POST['taskId']) || !is_numeric($_POST['taskId'])) {
            $changeTaskStatus = [
                "name" => "changeTaskStatusError",
                "description" => "invalid Action!"
            ];
            echo json_encode($changeTaskStatus);
            die();
        } else {
            changeTaskStatus($_POST['taskId']);
            echo json_encode(getIsDoneStatus($_POST['taskId']));
        }
        break;
    case "filterTasks":
        if (isset($_POST['sortMode']) || $_POST['sortMode'] == "created_at_DESC" || $_POST['sortMode'] == "created_at_ASC" || $_POST['sortMode'] == "is_done_checked" || $_POST['sortMode'] == "is_done_unchecked") {
            $filterdTask = filterTasks($_POST['sortMode'], $_POST['selectedFolderId']);
            echo showFilterdTasks($filterdTask);
        } else {
            $changeTaskStatus = [
                "name" => "filterTaskError",
                "description" => "invalid Action!"
            ];
            echo json_encode($changeTaskStatus);
            die();
        }
        break;
    default:
        diePage("Invalid Action!");
}
