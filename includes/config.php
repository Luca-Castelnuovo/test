<?php

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

return (object) array(
    'database' => (object) array(
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_DATABASE'),
        'user' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD')
    ),

    'github' => (object) array(
        'client_id' => getenv('GITHUB_CIENT_ID'),
        'client_secret' => getenv('GITHUB_CLIENT_SECRET'),
        'redirect' => 'https://test.lucacastelnuovo.nl'
    )
);

