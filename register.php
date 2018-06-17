<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if ($_GET['auth_code'] == 'valid') {
    $title = 'Register';
    $button_text = 'Submit';
    $type = 'register';
    $content = ['<input placeholder="Username" type="text" name="user_name" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>', '<input placeholder="Password" type="password" name="user_password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>', '<input type="hidden" name="auth_code" value="' . $_GET['auth_code'] . '">'];
} else {
    $title = 'Invite Code';
    $button_text = 'Check Invite Code';
    $content = ['<input placeholder="Invite Code" type="text" name="auth_code" autocomplete="off" class="text" autofocus value="' . $_GET['auth_code'] . '"> <i class="fa fa-barcode"></i>'];
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
        <form class="login">
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php foreach($content as $row) {echo $row;}?>
            <button id="submit"><i class="spinner"></i> <span class="state"><?= $button_text ?></span></button>
        </form>
    </div>
</body>

</html>
