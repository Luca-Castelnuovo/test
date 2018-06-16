<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

if (isset($_GET['submit'])) {
    switch ($_GET['type']) {
    case 'add':
        $title = 'Add Project';
        break;
    case 'edit':
        $title = 'Edit Project';
        break;
    case 'delete':
        $title = 'Delete Project';
        if ($mysqli->query("DELETE FROM projects WHERE id={$id} AND owner_id={$_SESSION['user_id']}")) {
            $content = ['<p>Project succesfully deleted!</p>', '<a href="home">Go Back</a>'];
        } else {
            $content = ['<p>Project not succesfully deleted!</p>', '<a href="home">Go Back</a>'];
        }
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
        $content = ["<p>Are you sure?</p>", "<div class='inline'><a class='dropdown-trigger btn' href='?type=delete&id={$_GET['id']}&submit&CSRFtoken=" . csrf_gen() . "'>Yes</a><a class='dropdown-trigger btn' href='home?project={$_GET['id']}'>No</a></div>"];
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
        <form class="login" style="padding-bottom: 20px;" method="post" action="project.php">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>

</html>
