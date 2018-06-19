<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
$error = clean_data($_GET['e']);

echo $error;

?>
