<?php

require($_SERVER['DOCUMENT_ROOT'] . '/includes/init.php');

if (isset($_GET['authenticate'])) {
    try {
        auth_get_authorization_code($GLOBALS['config']->client_id, 'basic:read');
    } catch (Exception $error) {
        redirect('/?reset', $error);
    }
}

if (isset($_GET['code'])) {
    try {
        $access_token_request = auth_get_access_token($GLOBALS['config']->client_id, $GLOBALS['config']->client_secret, $_GET['code'], $_GET['state']);
    } catch (Exception $error) {
        redirect('/?reset', $error);
    }

    if ($access_token_request['success']) {
        login($access_token_request['access_token']);
    }
}

if (isset($_GET['logout'])) {
    alert_set('You are logged out.');
    reset_session();
}

if (isset($_GET['reset'])) {
    reset_session();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    redirect('/home');
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="shortcut icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#2962ff">
    <link rel="manifest" href="/site.webmanifest">
</head>

<body>
<div class="row">
    <div class="col s12 m8 offset-m2 l4 offset-l4">
        <div class="card">
            <div class="card-action blue accent-4 white-text">
                <h3>Login</h3>
            </div>
            <div class="card-content">
                <div class="row center">
                    <a class="waves-effect waves-light btn-large blue accent-4" href="?authenticate">
                        Login with LTC
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <?= alert_display() ?>
</body>

</html>
