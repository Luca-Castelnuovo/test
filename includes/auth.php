<?php

$provider = new League\OAuth2\Client\Provider\Github([
    'clientId'          => $GLOBALS['config']->github->client_id,
    'clientSecret'      => $GLOBALS['config']->github->client_secret,
    'redirectUri'       => $GLOBALS['config']->github->redirect,
]);

function login($user)
{
    $username = $user->getNickname();

    $file = file_get_contents("/var/www/test.lucacastelnuovo.nl/public/users/Luca-Castelnuovo/configuration/testingplatform.json");
    $json = json_decode($file, true);

    if (!in_array($username, $json["allowed_users"])) {
        redirect("/?reset", 'Account not allowed');
    }

    mkdir("users/{$username}", 0770);

    $_SESSION['logged_in'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['id'] = $username;

    redirect('/home', 'You are logged in');
}


function loggedin()
{
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
