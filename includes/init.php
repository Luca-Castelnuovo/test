<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$GLOBALS['config'] = require $_SERVER['DOCUMENT_ROOT'] . '/../includes/config.php';

require $_SERVER['DOCUMENT_ROOT'] . '/../includes/auth.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/files.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/output.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/projects.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/security.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/sql.php';
require $_SERVER['DOCUMENT_ROOT'] . '/../includes/template.php';

// External
// require '/var/www/cdn.lucacastelnuovo.nl/include.php';
