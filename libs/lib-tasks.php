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
function addFolders($data)
{
    global $pdo;
    $sql = "select * from folders";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
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
