<?php

function login($access_token)
{
    try {
        $user = api_get_user($access_token);
    } catch (Exception $error) {
        response(false, $error->getMessage());
    }

    mkdir("users/{$user['username']}", 0770);

    $_SESSION['logged_in'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['access_token'] = $access_token;
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    log_action('3', 'auth.login', $_SERVER["REMOTE_ADDR"], $_SESSION['id']);
    redirect('/home', 'You are logged in');
}


function loggedin()
{
    try {
        api_get_token($_SESSION['access_token']);
    } catch (Exception $error) {
        redirect('/?reset', 'Please login');
    }

    if ((!$_SESSION['logged_in']) || ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) || (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800))) {
        redirect("/?reset", 'Please login');
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }
}


function reset_session()
{
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
    }

    session_destroy();
    session_start();

    redirect('/', $alert);
}
