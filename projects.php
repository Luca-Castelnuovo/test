<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

$show_button = true;
switch ($_GET['type']) {
case 'add':
    $title = 'Add Project';
    $content = ['<input placeholder="Project Name" type="text" name="project_name" autocomplete="off" class="text" autofocus> <i class="fa fa-user"></i>'];
    break;
case 'delete':
    $project_id = clean_data($_GET['project_id']);
    $title = 'Delete Project';
    $content = ["<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css'>", "<script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js'></script>", "<p>Are you sure?</p>", "<div class='inline'>", "<a class='dropdown-trigger btn' href='#' id='submit'>Yes</a><a class='dropdown-trigger btn' href='home'>No</a></div>"];
    $show_button = false;
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
               <input type="hidden" name="project_type" value="<?= clean_data($_GET['type']) ?>"/>
            <input type="hidden" name="project_id" value="<?= clean_data($_GET['project_id']) ?>"/>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php
                foreach($content as $row) {
                    echo $row;
                }

                if ($show_button) {
                    echo '<button id="submit"><span class="state">Submit</span></button>';
                }
            ?>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/projects.js"></script>
</body>

</html>
