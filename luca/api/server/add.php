<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

$user_email = escape_data($_POST['email']);
$user_password = escape_data(password_hash($_POST['password'], PASSWORD_BCRYPT));
$result_user = $mysqli->query("SELECT id FROM users WHERE email='$user_email'");
$user = $result_user->fetch_assoc();

if ($result_user->num_rows == 0) {
    $user_status = true;
} else {
    $user_status = false;
}

$api_key = escape_data($_GET['key']);
$api_token = escape_data($_POST['token']);
$result_api = $mysqli->query("SELECT api_token FROM api WHERE api_key='$api_key'");
$api = $result_api->fetch_assoc();

if ($api_token !== $api['api_token'] || $result_api->num_rows != 1) {
    $out = ["status" => false, "token_valid" => false];
    echo json_encode($out);
    exit;
}

if ($user_status) {
    $api_token = bin2hex(random_bytes(128));
    $mysqli->query("UPDATE api SET api_token='$api_token' WHERE api_key='$api_key'");
    $mysqli->query("INSERT INTO users (email,password) VALUES ('$user_email', '$user_password')");
    $out = ["status" => true, "token" => $api_token, "token_valid" => true];
    echo json_encode($out);
    exit;
} else {
    $api_token = bin2hex(random_bytes(128));
    $mysqli->query("UPDATE api SET api_token='$api_token' WHERE api_key='$api_key'");
    $out = ["status" => false, "token" => $api_token, "token_valid" => true];
    echo json_encode($out);
    exit;
}
