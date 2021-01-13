<?php defined('BASE_PATH') or die("Permision Denied!");
//define function for get current user id
function getCurrentUserID()
{
    return $_SESSION['login'];
}
//define function for logout
function logout()
{
    unset($_SESSION['login']);
}
//define function for check user login or not
function isLoggedIn()
{
    return isset($_SESSION['login']) ? true : false;
}
//define function for register user
function register($userData)
{
    global $pdo;
    $passHash = password_hash($userData['password'], PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (name,email,password) VALUES (:name , :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":name" => $userData['name'], ":email" => $userData['email'], ":password" => $passHash]);
    return $stmt->rowCount() ? true : false;
}
// define function for get login user by id
function getLoggedInUserInfo($userId)
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":userId" => $userId]);
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records ?? null;
}
// define function for get user by email
function getUserByEmail($email)
{
    global $pdo;
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $email]);
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records ?? null;
}
//define function for login user
function login($email, $pass)
{
    $user = getUserByEmail($email);
    if (is_null($user)) {
        return false;
    }
    if (password_verify($pass, $user->password)) {
        // login is successfull
        $_SESSION['login'] = $user->id;
        return true;
    }
    return false;
}
