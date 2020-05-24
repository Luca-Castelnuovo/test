<?php

return [
    'Free' => [
        'max_projects' => 1,
        'files_per_project' => 5,
        'allowed_extensions' => [
            'html',
            'css',
            'js'
        ]
    ],
    'Personal' => [
        'max_projects' => 3,
        'files_per_project' => 15,
        'allowed_extensions' => [
            'html',
            'css',
            'js'
        ]
    ],
    'Professional' => [
        'max_projects' => 10,
        'files_per_project' => 25,
        'allowed_extensions' => [
            'html',
            'css',
            'js',
            'json'
        ]
    ],
    'Admin' => [
        'max_projects' => 100,
        'files_per_project' => 250,
        'allowed_extensions' => [
            'html',
            'css',
            'js',
            'json',
            'php'
        ]
    ]
];
