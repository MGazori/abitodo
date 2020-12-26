<?php

//define function for error die page
function diePage($msg)
{
    echo "<div style='padding: 50px 30px; width: 80%; margin: 50px auto; border-radius: 10px; background-color: rgba(244,67,54 ,0.2); color: #f44336; font-family: sans-serif;'>$msg</div>";
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
