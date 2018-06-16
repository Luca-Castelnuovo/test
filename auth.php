<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/authentication/functions.php");

// GET - user submitted data using AJAX
// POST - in case user does not support javascript, we'll use POST instead
$username = ($_GET['username']) ? $_GET['username'] : $_POST['username'];
$password = ($_GET['password']) ? $_GET['password'] : $_POST['password'];

// if POST was used, display the message straight away
if ($_POST) {

    // check pass and username (for post)
    if (true) {
        echo 'post';
    }
} else {
    // check pass and username (for ajax)
    if (true) {
        echo 1;
    }
}
