<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

$note_token = escape_data($_POST['note_token']);
$result_note = $mysqli->query("SELECT text FROM notes WHERE token='$note_token'");
$note = $result_note->fetch_assoc();

if ($result_note->num_rows == 1) {
    $note_status = true;
} else {
    $note_status = false;
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

if ($note_status) {
    $api_token = bin2hex(random_bytes(128));
    $mysqli->query("UPDATE api SET api_token='$api_token' WHERE api_key='$api_key'");
    $mysqli->query("DELETE FROM notes WHERE token='$note_token'");
    $out = ["status" => true, "token" => $api_token, "token_valid" => true, "text" => $note['text']];
    echo json_encode($out);
    exit;
} else {
    $api_token = bin2hex(random_bytes(128));
    $mysqli->query("UPDATE api SET api_token='$api_token' WHERE api_key='$api_key'");
    $out = ["status" => false, "token" => $api_token, "token_valid" => true];
    echo json_encode($out);
    exit;
}
