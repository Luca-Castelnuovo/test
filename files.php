<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

$id = clean_data($_GET['id']);
$project_id = clean_data($_GET['project_id']);

$show_button = true;
switch ($_GET['type']) {
    case 'add':
        $title = 'Add File';
        $content = ['<div class="input-field"><label for="file_name">File Name</label><input type="text" name="file_name" class="text validate" id="file_name" autocomplete="off" required autofocus></div>', '<p>Please choose a file type</p>', '<p><label><input name="file_lang" type="radio" value="html" checked> <span>HTML</span></label></p>', '<p><label><input name="file_lang" type="radio" value="css"> <span>CSS</span></label></p>', '<p><label><input name="file_lang" type="radio" value="js"> <span>JavaScript</span></label></p>'];
        $button_text = 'Create File';
        break;

    case 'edit':
        $title = 'Edit File';
        $files = sql("SELECT file FROM files WHERE id='{$id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
        $project_name = $projects['project_name'];
        $file = "users/{$_SESSION['user_name']}/{$project_name}/{$files['file']}";
        $file_open = fopen($file, "r");
        $file_content = fread($file_open, filesize($file));
        fclose($file);
        $show_preview = null;
        $mode = substr($files['file'], strrpos($files['file'], '.') + 1);
        switch ($mode) {
        case 'html':
            $show_preview= '<iframe id=preview></iframe>';
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/js/test/codemirror/xml.js"></script><script src="https://cdn.lucacastelnuovo.nl/js/test/xml.js"></script>';
            break;
        case 'css':
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/js/test/codemirror/css.js"></script><script src="https://cdn.lucacastelnuovo.nl/js/test/css.js"></script><style>.CodeMirror{float:none;width:100%;}</style>';
            break;
        case 'js':
            $mode_js = '<script src="https://cdn.lucacastelnuovo.nl/js/test/codemirror/javascript.js"></script><script src="https://cdn.lucacastelnuovo.nl/js/test/javascript.js"></script><style>.CodeMirror{float:none;width:100%;}</style>';
            break;
        }
        $content = ['<textarea name="textarea" class="secret">' . $file_content . '</textarea>', '<div style="display: flex;"><textarea name="file_content" class="text" id="code" placeholder="Enter your code here..."></textarea>', $show_preview, '</div>'];
        $button_text = 'Save File';
        break;

    case 'delete':
        $title = 'Delete File';
        $content = ['<label for="delete">To confirm type `delete`</label>', '<input type="text" name="file_delete" class="text validate" id="delete" autocomplete="off" required autofocus>'];
        $button_text = 'Submit';
        break;

    default:
        logout();
        break;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <?php
    head($title, false);
    if ($_GET['type'] == 'edit') {
        echo '<link rel="stylesheet" href="https://cdn.lucacastelnuovo.nl/css/test/codemirror.css">';
    }
    ?>
</head>

<body>
<div class="wrapper" <?php if ($_GET['type'] == 'edit') {
        echo 'style="justify-content:flex-start;"';
    } ?>>
    <form class="login <?php if ($_GET['type'] == 'edit') {
        echo 'edit';
    } ?>" method="post">
        <div class="loader"><i class="spinner"></i></div>
        <div class="content">
            <input type="hidden" name="project_id" value="<?= clean_data($_GET['project_id']) ?>"/>
            <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
            <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
            <p class="title"><?= $title ?></p>
            <?php foreach ($content as $row) {
        echo $row;
    } ?>
            <button id="submit"><span class="state"><?= $button_text ?></span></button>
        </div>
    </form>
</div>
<?php
footer('files');

if ($_GET['type'] == 'edit') {
    //main
    echo '<script src="https://cdn.lucacastelnuovo.nl/js/test/codemirror.js"></script>';

    //addons
    echo '<script src="https://cdn.lucacastelnuovo.nl/js/test/codemirror/closetag.js"></script>';

    //modes
    echo $mode_js;

    //custom setting
    echo '<script>var contenttext = $("textarea[name=textarea]").val();myCodeMirror.getDoc().setValue(contenttext)</script>';

    //warn before closing page
    // echo "<script>$(document).ready(function(){
    //     $(window).on('beforeunload',function(){
    //         return '';
    //     });
    // });</script>";
} ?>
</body>

</html>
