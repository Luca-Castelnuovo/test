<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

//add
//edit
//delete

if (isset($_POST['submit'])) {
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
} else {
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
        <form class="login" method="post" action="project.php?$_GET['type']">
            <p class="title"><?= $title ?></p>
            <?php echo $content; ?>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <button id="submit"><span class="state"><?= $title ?></span></button>
        </form>
    </div>
</body>

</html>
