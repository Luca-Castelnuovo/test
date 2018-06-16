<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

$username = ($_GET['username']) ? $_GET['username'] : $_POST['username'];
$password = ($_GET['password']) ? $_GET['password'] : $_POST['password'];

if ($_POST) { //no js supported in browser

    // check pass and username (for post)
    if (true) {
        echo 'post';
    }
} else { //ajax
//    if ($mysqli->query("INSERT INTO users (username,password,active) VALUES ('106343', 'KCjFkwAx', 1)")) {
    if (true) {
        $out = ["status" => true, "username" => $username];
        echo json_encode($out);
    } else {
        $out = ["status" => false];
        echo json_encode($out);
    }
}
