<?php
include "bootstrap/init.php";
$email = "pattrick@tutorialspoint.com";
//form submition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];
    $params = $_POST;
    $siteBaseUrl = site_url();
    if ($action == 'register') {
        $uppercase = preg_match('@[A-Z]@', $params['password']);
        $lowercase = preg_match('@[a-z]@', $params['password']);
        $number = preg_match('@[0-9]@', $params['password']);
        if (empty($params['name']) || empty($params['email']) || empty($params['password'])) {
            message("Error: complete the form!", "error");
        } else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            message("Error: enter the valid email address", "error");
        } else if (!$uppercase || !$lowercase || !$number || strlen($params['password']) < 8) {
            message('Password should be at least 8 characters in length and should include at least one upper case letter, one number.', 'error');
        } else {
            $result = register($params);
            if (!$result) {
                message("Error: an error in registration!", "error");
            } else {
                login($params['email'], $params['password']);
                redirect(site_url(''));
            }
        }
    } else if ($action == 'login') {
        $result = login($params['email'], $params['password']);
        if (!$result) {
            message("email or password is incorrect", "error");
        } else {
            redirect(site_url(''));
        }
    }
}
// var_dump($_POST);
include "tpl/auth-tpl.php";
