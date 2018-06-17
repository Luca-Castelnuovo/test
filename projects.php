<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

switch ($_GET['type']) {
    case 'add':
        $title = 'Add Project';
        $content = ['<input placeholder="Project Name" type="text" name="project_name" autocomplete="off" class="text" autofocus> <i class="fa fa-file"></i>'];
        $button_text = 'Add my project';
        break;
    case 'delete':
        $title = 'Delete Project';
        $content = ['<input placeholder="To confirm type `delete` else type `no`" type="text" name="project_delete" autocomplete="off" class="text" autofocus> <i class="fa fa-trash"></i>'];
        $button_text = 'Submit';
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
    <form class="login">
        <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
        <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
        <p class="title"><?= $title ?></p>
        <?php foreach ($content as $row) {
            echo $row;
        } ?>
        <button id="submit"><span class="state"><?= $button_text ?></span></button>
    </form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/projects.js"></script>
</body>

</html>
