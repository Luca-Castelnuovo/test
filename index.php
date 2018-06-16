<?php require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");  alert(); if(isset($_GET['logout'])){logout('You are succesfully logged out!');}?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport">

    <link href=https://lucacastelnuovo.nl/images/favicon.ico rel="shortcut icon">
    <title>Login - test.lucacastelnuovo.nl</title>

    <link as="style" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" onload='this.rel="stylesheet"' rel="preload">
    <link as="style" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" onload='this.rel="stylesheet"' rel="preload">
    <link as="style" href="css/style.css" onload='this.rel="stylesheet"' rel="preload">
</head>

<body>
    <div class="wrapper">
        <form class="login" method="post" action="auth.php">
            <p class="title">Log in</p>
            <input placeholder="Username" type="text" name="username" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>
            <input placeholder="Password" type="password" name="password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>
            <button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/sweetalert2.all.js"></script>
</body>

</html>
