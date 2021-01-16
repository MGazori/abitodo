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
        checkValidUserByToken();
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
        checkValidUserByToken();
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
        checkValidUserByToken();
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
        checkValidUserByToken();
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
        checkValidUserByToken();
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
        checkValidUserByToken();
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
        checkValidUserByToken();
        if (isset($_POST['sortMode']) || $_POST['sortMode'] == "created_at_DESC" || $_POST['sortMode'] == "created_at_ASC" || $_POST['sortMode'] == "is_done_checked" || $_POST['sortMode'] == "is_done_unchecked") {
            $filterdTask = filterTasks($_POST['sortMode'], $_POST['selectedFolderId']);
            echo showTasks($filterdTask);
        } else {
            $filterTask = [
                "name" => "filterTaskError",
                "description" => "invalid Action!"
            ];
            echo json_encode($filterTask);
            die();
        }
        break;
    case "searchTasks":
        checkValidUserByToken();
        if (!isset($_POST['searchTxt']) || strlen($_POST['searchTxt']) < 2) {
            $searchTasks = [
                "name" => "searchTasksError",
                "description" => "The search text must be at least 3 letters long."
            ];
            echo json_encode($searchTasks);
            die();
        } else {
            $searchTasks = searchTasks($_POST['searchTxt'], $_POST['selectedFolderId']);
            echo showTasks($searchTasks);
        }
        break;
    case "getTasks":
        checkValidUserByToken();
        if (!isset($_POST['selectedFolderId'])) {
            $getTasks = [
                "name" => "getTasksError",
                "description" => "get tasks error."
            ];
            echo json_encode($getTasks);
            die();
        } else {
            $getTasks = getTasks($_POST['selectedFolderId']);
            echo showTasks($getTasks);
        }
        break;
    case "resetPasswordSendMail":
        if (!isset($_POST['userEmail'])) {
            $mailError = [
                "name" => "sendMailError",
                "description" => "Send Mail Error."
            ];
            echo json_encode($mailError);
            die();
        } else {
            if (!filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
                $response = [
                    "status" => 'error',
                    "description" => 'your email is invalid!'
                ];
                echo json_encode($response);
                break;
            }
            $checkUserByMail = checkUserByMail($_POST['userEmail']);
            if ($checkUserByMail !== false) {
                sendResetPassToken($_POST['userEmail']);
                $response = [
                    "status" => 'success',
                    "tokenForm" => "<form action='process/ajaxHandler.php' method='POST' id='reset-token'> <h1 id='reset-pass-title'>Reset Password</h1><label id='reset-label'>Enter the reset code sent to your email.</label><input id='user-reset-token' name='email-token' type='number' min='10000000' max='99999999' placeholder='Enter Code' required=''><button id='reset-pass-button' type='submit'>Submit</button><button id='send-again-token' onclick='location.reload()'>send again</button><a id='go-to-auth' href='auth.php'> Sign in / Sign up</a></form>"
                ];
                echo json_encode($response);
            } else {
                $response = [
                    "status" => "error",
                    "description" => "No users found with this email."
                ];
                echo json_encode($response);
            }
        }
        break;
    case "checkToken":
        if (!isset($_POST['userToken'])) {
            $tokenError = [
                "name" => "tokenError",
                "description" => "token Error."
            ];
            echo json_encode($tokenError);
            die();
        } else {
            $checkValidToken = checkValidToken($_POST['userToken']);
            if ($checkValidToken !== false) {
                $response = [
                    "status" => 'success',
                    "passForm" => "<form action='process/ajaxHandler.php' method='POST' id='reset-pass'> <h1 id='reset-pass-title'>Reset Password</h1><span class='forget-pass-msg' id='logged-out-notice'>After changing the password, all devices will be logged out</span><label id='reset-label'>Enter new password.</label> <input id='user-reset-pass' name='reset-pass' type='password' placeholder='Enter Password' minlength='8' required=''> <input id='user-reset-pass-again' name='reset-pass-again' type='password' placeholder='Enter Password Again' minlength='8' required=''> <button id='reset-pass-button' type='submit'>Change Password</button><a id='go-to-auth' href='auth.php'> Sign in / Sign up</a></form>"
                ];
                echo json_encode($response);
            } else {
                $response = [
                    "status" => 'error',
                    "description" => 'reset code is wrong',
                ];
                echo json_encode($response);
            }
        }
        break;
    case "resetPassword":
        if (!isset($_POST['userNewPass'])) {
            $newPassError = [
                "name" => "newPassError",
                "description" => "new pass Error."
            ];
            echo json_encode($tokenError);
            die();
        } else {
            if (!passwordIsStrong($_POST['userNewPass'])) {
                $response = [
                    "status" => 'error',
                    "description" => 'Password should be at least 8 characters in length and should include at least one lower and upper case letter, one number!',
                ];
                echo json_encode($response);
                break;
            }
            $changeUserPass = changeUserPass($_SESSION['UMAIL'], $_POST['userNewPass']);
            if ($changeUserPass === 1) {
                logoutAllActiveSession($_SESSION['UMAIL']);
                $response = [
                    "status" => 'success',
                    "description" => 'reset password is successfully!',
                    "redirectAddress" => site_url('auth.php')
                ];
                unset($_SESSION['UMAIL']);
                unset($_SESSION['URPTK']);
                echo json_encode($response);
            } else {
                $response = [
                    "status" => 'error',
                    "description" => 'reset password failed!',
                ];
                echo json_encode($response);
            }
        }
        break;
    default:
        diePage("Invalid Action!");
}
