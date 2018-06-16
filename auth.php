<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

$success = false;

$username = clean_data($_GET['username']);
$password = clean_data($_GET['password']);

$result = $mysqli->query("SELECT * FROM users WHERE username='$username'");

if ($result->num_rows != 0) {
    if (password_verify($_POST['password'], $user['password'])) {
        $ip = $_SESSION['ip'] = ip();
        $username = $_SESSION['user_name'] = $user['username'];
        $_SESSION['active'] = $user['active'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['logged_in'] = true;

        $text = date('Y-m-d H:i:s') . '	:	' . $ip . '	:	' . $username . PHP_EOL;
        $file = fopen("ip-login.txt", "a+");
        fwrite($file, $text);
        $success = true;
    }
}

if ($success) {
    $out = ["status" => true, "username" => $username];
    echo json_encode($out);
} else {
    $out = ["status" => false];
    echo json_encode($out);
}
