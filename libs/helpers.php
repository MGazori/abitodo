<?php defined('BASE_PATH') or die("Permision Denied!");

//define function for error die page
function diePage($msg)
{
    echo "<div style='padding: 50px 30px; width: 80%; margin: 50px auto; border-radius: 10px; background-color: rgba(244,67,54 ,0.2); color: #f44336; font-family: sans-serif;'>$msg</div>";
    die();
}
//define function for show message
function message($msg, $cssClass = 'info')
{
    echo "<div class='$cssClass' style='padding: 30px 30px; width: 80%; margin: 10px auto; border-radius: 10px; background-color: rgba(244,67,54 ,0.2); color: #f44336; font-family: sans-serif;'>$msg</div>";
}
//define function for redirect
function redirect($address)
{
    header("location:" . $address);
    die();
}
//define function for validate ajax request
function isAjaxRequest()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    } else {
        return false;
    }
}
//define function for using full url in project
function site_url($uri = '')
{
    return BASE_URL . $uri;
}
//beadufull var_dump
function dd($data)
{
    echo "<pre style='color: #455A64;font-size: 18px;position:relative;background-color:#ffffff;border-radius:10px;z-index: 999;margin: 10px;padding: 20px;border-left: 5px solid #2196F3;'>";
    var_dump($data);
    echo "</pre>";
}
