<?php

use lucacastelnuovo\Helpers\Arr;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

function config($key, $fallback = null)
{
    static $config;

    if (is_null($config)) {
        $config = [
            'analytics' => [
                'enabled' => true,
                'domainId' => '0cfb7d34-c3b1-492f-8552-129dab201b09',
                'options' => '{"localhost": false, "detailed": true }'
            ],
            'app' => [
                'url' => env('APP_URL'),
                'id' => env('APP_ID'),
                'variants' => [
                    'Free' => [
                        'max_projects' => 1,
                        'files_per_project' => 5
                    ],
                    'Personal' => [
                        'max_projects' => 3,
                        'files_per_project' => 15
                    ],
                    'Professional' => [
                        'max_projects' => 10,
                        'files_per_project' => 25
                    ],
                ],
            ],
            'database' => [
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ],
            'files' => [
                'allowed_extensions' => [
                    'html',
                    'css',
                    'js',
                    'json'
                ]
            ]
        ];
    }

    return Arr::get($config, $key, $fallback);
}
