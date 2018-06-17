<?php


//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE
//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE
//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE
//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE
//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE
//LOG IP AND USERNAME OF EVRYTHING THAT HAPPENS IN THIS FILE


require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

$id = clean_data($_GET['id']);
$project_id = clean_data($_GET['project_id']);

if (isset($_GET['submit'])) {
    $show_button = false;
    switch ($_GET['type']) {
    case 'add':
        csrf_val(clean_data($_POST['CSRFtoken']));
        $title = 'Add Project';
        $project_name = clean_data($_POST['project_name']);
        if (sql("INSERT INTO files (owner_id, project_name) VALUES ('{$_SESSION['user_id']}', '{$project_name}')")) {
            $content = ['<p>Project succesfully created!</p>', '<a href="home">Go Back</a>'];
        } else {
            $content = ['<p>Project not succesfully created!</p>', '<a href="home">Go Back</a>'];
        }
        break;
    case 'edit':
        csrf_val(clean_data($_POST['CSRFtoken']));
        $title = 'Edit Project';
        $project_name = clean_data($_POST['project_name']);
        if (sql("UPDATE files SET project_name='{$project_name}' WHERE id='{$id}' AND owner_id='{$_SESSION['user_id']}'")) {
            $content = ['<p>Project succesfully edited!</p>', '<a href="home">Go Back</a>'];
        } else {
            $content = ['<p>Project not succesfully edited!</p>', '<a href="home">Go Back</a>'];
        }
        break;
    case 'delete':
        csrf_val(clean_data($_GET['CSRFtoken']));
        $title = 'Delete Project';
        $files = sql("SELECT file_name FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $file_name = $files['file_name'];
        if (sql("DELETE FROM files WHERE id='{$id}' AND owner_id='{$_SESSION['user_id']}'")) {
            unlink("users/{$_SESSION['user_name']}/{$file_name}");
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
        $title = 'Add File';

        //make radio buttons for (js,css,html)
        $content = ['<input placeholder="Project Name" type="text" name="project_name" autocomplete="off" class="text" autofocus> <i class="fa fa-user"></i>'];

        break;
    case 'edit':
        $title = 'Edit File';
        $files = sql("SELECT * FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $file_name = $files['file_name'];
        $file_content = $files['file_content'];

        //make a textarea for code
        $content = ['<input placeholder="Project Name" type="text" name="file_name" autocomplete="off" class="text" value="' . $file_name . '" autofocus> <i class="fa fa-user"></i>', ''];


        break;
    case 'delete':
        $title = 'Delete File';
        $content = ["<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css'>", "<script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js'></script>", "<p>Are you sure?</p>", "<div class='inline'><a class='dropdown-trigger btn' href='?type=delete&id={$id}&submit&CSRFtoken=" . csrf_gen() . "'>Yes</a><a class='dropdown-trigger btn' href='home?project={$project_id}'>No</a></div>"];
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
        <form class="login" method="post" action="files?type=<?= $_GET['type'] ?>&id=<?= $_GET['id'] ?>&submit">
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
</body>

</html>
