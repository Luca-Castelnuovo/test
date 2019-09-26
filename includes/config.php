<?php

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

$GLOBALS['config'] = (object) array(
    'database' => (object) array(
        'host' => $_ENV['DB_HOST'],
        'database' => $_ENV['DB_DATABASE'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD']
    ),

    'github' => (object) array(
        'client_id' => $_ENV['GITHUB_CIENT_ID'],
        'client_secret' => $_ENV['GITHUB_CLIENT_SECRET'],
        'redirect' => 'https://test.lucacastelnuovo.nl'
    )
);
