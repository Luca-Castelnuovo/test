<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

$success = true;

$user_name = clean_data($_GET['username']);
$user_password = clean_data($_GET['password']);

if (csrf_val_ajax(clean_data($_GET['CSRFtoken']))) {
    error();
}

$result = $mysqli->query("SELECT * FROM users WHERE user_name='$user_name'");

if ($result->num_rows != 0) {
    $user = $result->fetch_assoc();
    if (password_verify($user_password, $user['user_password'])) {
        $_SESSION['ip'] = ip();
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_active'] = $user['user_active'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = 1;

        $text = date('Y-m-d H:i:s') . '	:	' . $_SESSION['ip'] . '	:	' . $_SESSION['user_name'] . PHP_EOL;
        $file = fopen("login.txt", "a+");
        fwrite($file, $text);

        success($_SESSION['user_name']);
    } else {
        error();
    }
} else {
    error();
}

function error()
{
    $out = ["status" => false];
    echo json_encode($out);
    exit;
}

function success($username)
{
    $out = ["status" => true];
    echo json_encode($out);
    exit;
}
