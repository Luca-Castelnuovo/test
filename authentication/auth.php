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

	//recipient - change this to your name and email
	$to = 'Your Name <your@email.com>';
	//sender
	$from = $username . ' <' . $password . '>';

	//subject and the html message
	$subject = 'Comment from ' . $username;
	$message = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head></head>
	<body>
	<table>
		<tr><td>Name</td><td>' . $username . '</td></tr>
		<tr><td>Email</td><td>' . $password . '</td></tr>
	</table>
	</body>
	</html>';

	//send the mail
	$result = sendmail($to, $subject, $message, $from);

	//if POST was used, display the message straight away
	if ($_POST) {
		if ($result) echo 'Thank you! We have received your message.';
		else echo 'Sorry, unexpected error. Please try again later';

	//else if GET was used, return the boolean value so that
	//ajax script can react accordingly
	//1 means success, 0 means failed
	} else {
		echo $result;
	}

//if the errors array has values
} else {
	//display the errors message
	for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
	echo '<a href="index.php">Back</a>';
	exit;
}


//Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: ' . $from . "\r\n";

//	$result = mail($to,$subject,$message,$headers);
//
//	if ($result) return 1;
//	else return 0;

    $array = [$to, $subject, $message, $from];
    var_dump();
}
