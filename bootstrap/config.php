<?php

use App\Helpers\ArrayHelper;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

function config($key, $fallback = null)
{
    static $config;

    if (is_null($config)) {
        $config = [
            'analytics' => [
                'enabled' => false,
                'domainId' => '0cfb7d34-c3b1-492f-8552-129dab201b09',
                'options' => '{ "localhost": false, "detailed": true }'
            ],
            'app' => [
                'url' => env('APP_URL'),
                'id' => env('APP_ID')
            ],
            'database' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ],
            'variants' => [ // TODO: set correct tiers
                'Free' => [
                    'files_per_project' => 3,
                    'max_projects' => 2
                ],
                'Peersonal' => [
                    'files_per_project' => 3,
                    'max_projects' => 2
                ],
                'Professional' => [
                    'files_per_project' => 3,
                    'max_projects' => 2
                ],
            ]
        ];
    }

    return ArrayHelper::get($config, $key, $fallback);
}