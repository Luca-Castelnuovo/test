<?php

function class_auto_loader($class)
{
    $class = strtolower($class);
    $the_path = "includes/{$class}.php";

    if (file_exists($the_path)) {
        require_once($the_path);
    } else {
        die("This file name {$class}.php was not found!");
    }
}

spl_autoload_register('class_auto_loader');

function redirect($location)
{
    header('Location: ' . $location);
    exit;
}
