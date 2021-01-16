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
//define function for check password strength
function passwordIsStrong($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        return false;
    }
    return true;
}
//define function for check user by exist by mail
function checkUserByMail($email)
{
    global $pdo;
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_OBJ);
    return $records ?? null;
}
// define function for send reset pass token to user mail and save to session
function sendResetPassToken($email)
{
    $resetPassToken = rand(10000000, 99999999);
    $_SESSION['UMAIL'] = $email;
    // $_SESSION['URPTK'] = md5($resetPassToken);
    $_SESSION['URPTK'] = md5(12345678);
    $resetPassSubject = 'reset password code for abitodo';
    $resetPassMassage = "your code is: $resetPassToken";
    mail($_SESSION['UMAIL'], $resetPassSubject, $resetPassMassage, "From: " . SITE_MAIL);
}
//define function for check valid token
function checkValidToken($token)
{
    if (md5($token) === $_SESSION['URPTK']) {
        return true;
    }
    return false;
}
//define function for change user password
function changeUserPass($email, $password)
{
    global $pdo;
    $passHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE `users` SET `password` = :pass WHERE `email` = :email;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $email, ":pass" => $passHash]);
    $result = $stmt->rowCount();
    if ($result === 1) {
        $resetPassSubject = 'reset password is successfully';
        $resetPassMassage = "Your abitodo password has been changed!";
        mail($_SESSION['UMAIL'], $resetPassSubject, $resetPassMassage, "From: " . SITE_MAIL);
    }
    return $result;
}
//define function for logged out all session
function logoutAllActiveSession($email)
{
    $userId = checkUserByMail($email)->id;
    global $pdo;
    $sql = "DELETE FROM `tokens` WHERE `user_id` = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":userId" => $userId]);
    return $stmt->rowCount();
}
//define function for check token cookie and database token for validation
function checkValidUserByToken()
{
    if (!isLoggedIn() || is_null(getUserIdByToken($_COOKIE['SULGI'])) || getUserIdByToken($_COOKIE['SULGI']) == false) {
        $redirectToAuth = site_url("auth.php");
        echo "<script>window.location.replace('$redirectToAuth')</script>";
        die();
    }
}
