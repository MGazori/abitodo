<?php
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
    $stmt->execute(["folderID" => $data]);
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