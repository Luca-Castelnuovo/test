<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

if (isset($_GET['submit'])) {
    $show_button = false;
    switch ($_GET['type']) {
    case 'add':
        csrf_val(clean_data($_POST['CSRFtoken']));
        $title = 'Add Project';
        $file_name = clean_data($_POST['file_name']);
        $file_type = clean_data($_POST['lang']);
        switch ($file_type) {
            case 'html':
                $file = $file_name . '.html';
                break;
            case 'css':
                $file = $file_name . '.css';
                break;
            case 'js':
                $file = $file_name . '.js';
                break;
            default:
                logout('Hack attempt detected!');
        }
        $project = sql("SELECT id FROM projects WHERE id='{$project_id}' AND owner_id='{$_SESSION['user_id']}'");
        if ($project->num_rows == 0) {header('Location: /home');exit;}

        if (sql("INSERT INTO files (owner_id, project_id, file) VALUES ('{$_SESSION['user_id']}', '{$project_id}', '{$file}')")) {
            $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
            $project_name = $projects['project_name'];
            fopen("users/{$_SESSION['user_name']}/{$project_name}/{$file}", "w");
            fclose("users/{$_SESSION['user_name']}/{$project_name}/{$file}");
            $content = ['<p>File succesfully created!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        } else {
            $content = ['<p>File not succesfully created!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        }
        break;
    case 'edit':
        csrf_val(clean_data($_POST['CSRFtoken']));
        $title = 'Edit Project';
        $file_content = $_POST['file_content'].PHP_EOL;
        $files = sql("SELECT file FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $project_name = $projects['project_name'];
        $file = "users/{$_SESSION['user_name']}/{$project_name}/{$files['file']}";
        $file_open = fopen($file, "w");
        if (fwrite($file_open, $file_content)) {
            fclose($file);
            $content = ['<p>File succesfully updated!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        } else {
            fclose($file);
            $content = ['<p>File not succesfully updated!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        }
        break;
    case 'delete':
        csrf_val(clean_data($_GET['CSRFtoken']));
        $title = 'Delete Project';
        $files = sql("SELECT file FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $file = $files['file'];
        $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $project_name = $projects['project_name'];
        if (sql("DELETE FROM files WHERE id='{$id}' AND owner_id='{$_SESSION['user_id']}'")) {
            unlink("users/{$_SESSION['user_name']}/{$project_name}/{$file}");
            $content = ['<p>File succesfully deleted!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        } else {
            $content = ['<p>File not succesfully deleted!</p>', '<a href="home?project=' . $project_id . '">Go Back</a>'];
        }
        break;
}

$id = clean_data($_GET['id']);
$project_id = clean_data($_GET['project_id']);

$show_button = true;
switch ($_GET['type']) {
case 'add':
    $title = 'Add File';
    $content = ['<input placeholder="File Name" type="text" name="file_name" autocomplete="off" class="text" autofocus> <i class="fa fa-file"></i>', '<br><p>Please choose a file type</p>', '<p><label><input checked name="lang" type="radio" value="html"> <span>HTML</span></label></p>', '<p><label><input name="lang" type="radio" value="css"> <span>CSS</span></label></p>', '<p><label><input name="lang" type="radio" value="js"> <span>JavaScript</span></label></p>'];
    break;

case 'edit':
    $title = 'Edit File';
    $files = sql("SELECT file FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
    $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
    $project_name = $projects['project_name'];
    $file = "users/{$_SESSION['user_name']}/{$project_name}/{$files['file']}";
    $file_open = fopen($file, "r");
    $file_content = fread($file_open,filesize($file));
    fclose($file);
    $content = ['<textarea class="text" name="file_content" rows="50" cols="50" placeholder="Enter your code here...">' . $file_content . '</textarea>'];
    break;

case 'delete':
    $title = 'Delete File';
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
            <input type="hidden" name="project_id" value="<?= clean_data($_GET['project_id']) ?>"/>
            <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
            <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php foreach($content as $row) {echo $row; } ?>
            <button id="submit"><span class="state"><?= $button_text ?></span></button>
        </form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/files.js"></script>
</body>

</html>
