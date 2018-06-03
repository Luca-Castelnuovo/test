<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

login();

$api_key = escape_data($_GET['api_key']);

$mysqli->query("DELETE FROM api WHERE api_key='$api_key'");

header('Location: index.php');
