<?php defined('BASE_PATH') or die("Permision Denied!");
//define function for get current user id
function getCurrentUserID()
{
    return getUserIdByToken($_COOKIE['SULGI'])->user_id;
}
//define function for logout
function logout()
{
    deleteTokenRowByToken($_COOKIE['SULGI']);
    setcookie("SULGI", "", time() - 3600, "/");
}
//define function for check user login or not
function isLoggedIn()
{
    return isset($_COOKIE['SULGI'])  ? true : false;
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
//define function for insert login user data in database
function createLoginToken($userId, $token)
{
    global $pdo;
    $sql = "INSERT INTO tokens (user_id, token, expire_at) VALUES (:user_id, :hashToken, NOW() + INTERVAL 1728000 SECOND);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":user_id" => $userId, ":hashToken" => $token]);
    return $stmt->rowCount() ? true : false;
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
        $token = hash_hmac('sha256', "$user->id", bin2hex(random_bytes(32)));
        createLoginToken($user->id, $token);
        setcookie("SULGI", $token, time() + 1728000, "/");
        return true;
    }
    return false;
}
//define function for get user id by token
function getUserIdByToken($token)
{
    global $pdo;
    $sql = "SELECT user_id FROM tokens WHERE token = :loginToken";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':loginToken', $token, PDO::PARAM_STR);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records ?? null;
}
//define function for delete token row when logged out
function deleteTokenRowByToken($token)
{

    global $pdo;
    $sql = "DELETE FROM tokens WHERE token = :loginToken";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":loginToken" => $token]);
    return $stmt->rowCount();
}
//define function for delete expired row
function deleteExpireTokenRow()
{
    global $pdo;
    $sql = "DELETE FROM tokens WHERE expire_at < now()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}
