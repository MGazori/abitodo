<?php defined('BASE_PATH') or die("Permision Denied!");
// define function for get folders list
function getFolders()
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT * FROM folders WHERE user_id = $userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}
// define function for add folders list
function addFolder($folderName)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "INSERT INTO folders (name,user_id) VALUES (:folderName , :user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":folderName" => $folderName, ":user_id" => $userId]);
    $records = $pdo->lastInsertId();
    return $records;
}
// define function for remove folder
function deleteFolders($data)
{
    global $pdo;
    $sql = "DELETE FROM folders where id = :folderID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":folderID" => $data]);
    return $stmt->rowCount();
}
// define function for return information added folder
function addNewFolderRow($lastInsertFolderId)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT id,name FROM folders WHERE id = :lastAddId AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":lastAddId" => $lastInsertFolderId, ":user_id" => $userId]);
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}
// define function for get tasks list
function getTasks($data = null)
{
    global $pdo;
    if (!is_null($data) && $data !== "all") {
        $folderId = "AND folder_id = $data";
    } else {
        $folderId = "";
    }
    $userId = getCurrentUserID();
    $sql = "SELECT * FROM tasks WHERE user_id = $userId $folderId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}
// define function for remove folder

function deleteTasks($data)
{
    global $pdo;
    $sql = "DELETE FROM tasks where id = :taskId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskId" => $data]);
    return $stmt->rowCount();
}

//define function for filter task by folder
function filterTasksByFolder($data)
{
    $tasks = getTasks($data);
    foreach ($tasks as $task) { ?>
        <li data-task-id="<?= $task->id ?>" <?= ($task->is_done) ? "class='checked taskRow'" : "class='taskRow'" ?>><i class="fa <?= ($task->is_done) ? "fa-check-square-o" : 'fa-square-o' ?>"></i><span><?= $task->title ?></span>
            <div class="info">
                <span class="task-created-at"><?= $task->created_at ?></span>
                <button class="removeTaskBtn" data-task-id="<?= $task->id ?>"></button>
            </div>
        </li>
    <?php
    }
}
// define function for add task
function addTask($taskTitle, $folderId)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "INSERT INTO tasks (title,user_id,folder_id) VALUES (:taskTitle , :user_id, :folder_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskTitle" => $taskTitle, ":user_id" => $userId, ":folder_id" => $folderId]);
    $records = $pdo->lastInsertId();
    return $records;
}
//get single task
function getSingleTask($data = null)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT * FROM tasks WHERE user_id = $userId AND id = :taskId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskId" => $data]);
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records;
}
//change task status
function changeTaskStatus($data)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "UPDATE tasks SET is_done = 1 - is_done WHERE user_id = $userId AND id = :taskId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskId" => $data]);
    $records = $stmt->rowCount();
    return $records;
}
//get single task show is_done status
function getIsDoneStatus($data = null)
{
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT is_done FROM tasks WHERE user_id = $userId AND id = :taskId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskId" => $data]);
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records;
}
//filter tasks
function filterTasks($filterMode, $selectedFolder = null)
{
    if ($filterMode == "created_at_ASC") {
        $filterSql = "ORDER BY tasks.created_at ASC";
    } else if ($filterMode == "created_at_DESC") {
        $filterSql = "ORDER BY tasks.created_at DESC";
    } else if ($filterMode == "is_done_checked") {
        $filterSql = "AND is_done = 1";
    } else if ($filterMode == "is_done_unchecked") {
        $filterSql = "AND is_done = 0";
    }
    if ($selectedFolder == "all") {
        $folderFilter = "";
    } else {
        $folderFilter = "AND folder_id = $selectedFolder";
    }
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id $folderFilter $filterSql";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":user_id" => $userId]);
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}
//define function fo search between tasks
function searchTasks($searchtext, $selectedFolder = null)
{
    if ($selectedFolder == "all") {
        $folderFilter = "";
    } else {
        $folderFilter = "AND folder_id = $selectedFolder";
    }
    global $pdo;
    $userId = getCurrentUserID();
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id AND title LIKE :searchtxt $folderFilter";
    $stmt = $pdo->prepare($sql);
    $searchtxt = "%" . $searchtext . "%";
    $stmt->bindParam(':searchtxt', $searchtxt, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records ?? null;
}
//show tasks 
function showTasks($data)
{
    $tasks = $data;
    foreach ($tasks as $task) { ?>
        <li data-task-id="<?= $task->id ?>" <?= ($task->is_done) ? "class='checked taskRow'" : "class='taskRow'" ?>><i class="fa <?= ($task->is_done) ? "fa-check-square-o" : 'fa-square-o' ?>"></i><span><?= $task->title ?></span>
            <div class="info">
                <span class="task-created-at"><?= $task->created_at ?></span>
                <button class="removeTaskBtn" data-task-id="<?= $task->id ?>"></button>
            </div>
        </li>
<?php
    }
}
