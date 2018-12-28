<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/init.php';

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
    <!-- Config -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="manifest" href="/manifest.json"></link>
    <title>Login || Test</title>

    <!-- SEO -->
    <link href="https://test.lucacastelnuovo.nl" rel="canonical">
    <meta content="A system to develop your quick ideas" name="description">

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.lucacastelnuovo.nl/general/css/materialize.css">
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
    <script src="https://cdn.lucacastelnuovo.nl/general/js/materialize.js"></script>
    <?= alert_display() ?>
</body>

</html>
