<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

//add
//edit
//delete

switch ($_GET['type']) {
    case 'add':
        $title = 'Add Project';
        break;
    case 'edit':
        $title = 'Edit Project';
        break;
    case 'delete':
        $title = 'Delete Project';
        break;

    default:
        logout('Hack attempt detected!');
        break;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport">
    <link href=https://lucacastelnuovo.nl/images/favicon.ico rel="shortcut icon">
    <title><?= $title ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,700">
</head>


<body>
    <div class="wrapper">
        <form class="login" method="post" action="auth.php">
            <p class="title">Log in</p>
            <input placeholder="Username" type="text" name="username" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>
            <input placeholder="Password" type="password" name="password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <?php alert($_GET['alert']); ?>
</body>

</html>
