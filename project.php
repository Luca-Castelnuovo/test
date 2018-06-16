<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

if (isset($_POST)) {
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
    $show_button = true;
    switch ($_GET['type']) {
    case 'add':
        $title = 'Add Project';
        $content = ['<p>item1</p>', '<p>item2</p>', '<p>item3</p>'];
        break;
    case 'edit':
        $title = 'Edit Project';
        $content = ['<p>item4<p>', '<p>item5</p>'];
        break;
    case 'delete':
        $title = 'Delete Project';
        $content = ["<p>Are you sure?</p>", "<form method='post' action='?type=delete&id={$_GET['id']}'><button class='dropdown-trigger btn inline'>Yes</button></form><a class='dropdown-trigger btn inline' href='home?project={$_GET['id']}'>No</a>"];
        $show_button = false;
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
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php
                foreach($content as $row) {
                    echo $row;
                }

                if ($show_button) {
                    echo '<button id="submit"><span class="state">Submit</span></button>';
                }

                echo $show_button;
            ?>
        </form>
    </div>
</body>

</html>
