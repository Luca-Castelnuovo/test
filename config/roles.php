<?php

return [
    'user' => [
        'max_projects' => 1,
        'files_per_project' => 5,
        'allowed_extensions' => [
            'html',
            'css',
            'js',
        ],
    ],
    'userplus' => [
        'max_projects' => 3,
        'files_per_project' => 15,
        'allowed_extensions' => [
            'html',
            'css',
            'js',
            'json',
        ],
    ],
    'admin' => [
        'max_projects' => 100,
        'files_per_project' => 250,
        'allowed_extensions' => [
            'html',
            'css',
            'js',
            'json',
            'php',
        ],
    ],
];
