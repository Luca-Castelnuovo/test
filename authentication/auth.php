<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/authentication/functions.php");

//GET - user submitted data using AJAX
//POST - in case user does not support javascript, we'll use POST instead
$username = ($_GET['username']) ? $_GET['username'] : $_POST['username'];
$password = ($_GET['password']) ?$_GET['password'] : $_POST['password'];

//flag to indicate which method it uses. If POST set it to 1
if ($_POST) $post=1;

//Simple server side validation for POST data, of course,
//you should validate the email
if (!$username) $errors[count($errors)] = 'Please enter your username.';
if (!$password) $errors[count($errors)] = 'Please enter your password.';

//if the errors array is empty, send the mail
if (!$errors) {
	//if POST was used, display the message straight away
	if ($_POST) {

    //check pass and username (for post)
    if (true) {
        echo 'post';
    }
	} else {
		//check pass and username (for ajax)
    if (true) {
        echo 1;
    }
	}

} else {
	//display the errors message
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="index.php">Back</a>';
	exit;
}
