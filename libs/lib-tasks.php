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
    $sql = "DELETE FROM tasks where id = :taskID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":taskID" => $data]);
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
