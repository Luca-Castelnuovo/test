<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $api_key = bin2hex(random_bytes(16));
    $api_token = bin2hex(random_bytes(128));
    $api_name = escape_data($_POST['api_name']);

    $mysqli->query("INSERT INTO api (api_key,api_token,api_name) VALUES ('$api_key', '$api_token', '$api_name')");

    header('Location: index.php');
}

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>API gen</title>
    </head>

    <body>
        <form action="gen.php" method="post">
            <input type="text" required="required" name="api_name" autocomplete="off" autofocus placeholder="API client name">
            <input type="submit">
        </form>
    </body>

    </html>
