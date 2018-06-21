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
        $content = ['<label for="code">Enter your code here...</label>', '<textarea name="file_content" class="text" id="code" rows="30" required>' . $file_content . '</textarea>'];
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

<?php head($title); ?>

<body>
<div class="wrapper">
    <form class="login <?php if ($_GET['type'] == 'edit') {echo 'edit';} ?>" method="post">
        <input type="hidden" name="project_id" value="<?= clean_data($_GET['project_id']) ?>"/>
        <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
        <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
        <p class="title"><?= $title ?></p>
        <?php foreach ($content as $row) {echo $row;} ?>
        <button id="submit"><span class="state"><?= $button_text ?></span></button>
    </form>
</div>
<?php footer('files'); ?>
</body>

</html>
