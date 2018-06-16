<?php

//toggle 2fa
$fa = true;

// connect to db
$mysqli = new mysqli('localhost:8889', 'root', 'root', 'main_db');


// sanitize user input
function escape_data($data)
{
    global $mysqli;
    $data = $mysqli->escape_string($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login()
{
    if ($fa) {
        $allow = ["86.87.160.103"];

        if(!in_array($_SERVER['REMOTE_ADDR'], $allow)) {
            echo 'Access Denied!';
            exit;
        }
    }
}
