<?php
include "bootstrap/init.php";
if (isLoggedIn()) {
    redirect(site_url(""));
}
//form submition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];
    $params = $_POST;
    $siteBaseUrl = site_url();
    if ($action == 'register') {
        if (empty($params['name']) || empty($params['email']) || empty($params['password'])) {
            message("Error: complete the form!", "error");
        } else if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            message("Error: enter the valid email address", "error");
        } else if (!passwordIsStrong($params['password'])) {
            message('Password should be at least 8 characters in length and should include at least one lower and upper case letter, one number!', 'error');
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
            deleteExpireTokenRow();
            redirect(site_url(''));
        }
    }
}
//check if user request for reset password show reset template
if ($_GET['action'] == 'reset-password') {
    include "tpl/reset-pass-tpl.php";
    die();
}
include "tpl/auth-tpl.php";
