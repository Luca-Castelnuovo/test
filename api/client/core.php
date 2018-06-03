<?php

session_start();

function api_globals()
{
    $globals = [
        'api_server' => 'http://api.ta-soest.nl',
        'api_token_file' => 'api_token.txt',
        'api_key' => 'ae0260bf5b3d5d257f84024ec6a31309'
    ];

    return $globals;
}

function ses_clear()
{
    session_unset();
    session_destroy();
    session_start();
}

function login()
{
    if ($_SESSION['logged_in'] != 1) {
        $_SESSION['error'] = 'Access Denied!';
        header("location: /");
        exit;
    }
}

function head($title)
{
    echo '
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>' . $title . '</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>';
}

function alert()
{
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        echo '<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> ' . $error . '</div>';
    }

    if (isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
        echo '<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> ' . $success . '</div>';
    }
}

function api_token()
{
    $api_globals = api_globals();

    if (isset($_SESSION['token'])) {
        $token = $_SESSION['token'];
    } else {
        $token = file_get_contents($api_globals['api_token_file']);
    }

    return $token;
}

function api_post($url, $data)
{
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $api_response = file_get_contents($url, false, $context);

    return $api_response;
}

function api_request($api_type, $user_email, $user_password, $api_extra_param = null)
{
    $api_globals = api_globals();

    $url = "{$api_globals['api_server']}/{$api_type}.php?key={$api_globals['api_key']}";

    $data_post = [
        'token' => api_token(), 'email' => $user_email, 'password' => $user_password
    ];

    if (isset($api_extra_param)) {
        $data_post = array_merge($data_post, $api_extra_param);
    }

    $api_response = json_decode(api_post($url, $data_post), true);

    if ($api_response['token_valid'] && isset($api_response['token'])) {
        $_SESSION['token'] = $api_response['token'];
        file_put_contents($api_globals['api_token_file'], $_SESSION['token']);
    } else {
        header('Location: /?api');
        exit;
    }

    return $api_response;
}
