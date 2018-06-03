<?php

require_once("includes/init.php");

if ($session->is_logged_in()) {redirect('index.php');}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user_found = User::verify_user($username, $password);

    if ($user_found) {
        $session->login();
        redirect('../index.php');
    } else {
        $the_message  = 'Your username or password are incorrect!';
    }

} else {
    $username = null;
    $password = null;
}
