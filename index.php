<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php"); if(isset($_GET['logout'])){logout('You are succesfully logged out!');} ?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport">
    <link href=https://lucacastelnuovo.nl/images/favicon.ico rel="shortcut icon">
    <title>Log In</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,700">
</head>

<body>
    <div class="wrapper">
        <form class="login">
            <p class="title">Log in</p>
            <input placeholder="Username" type="text" name="user_name" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>
            <input placeholder="Password" type="password" name="user_password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/login.js"></script>
</body>

</html>
