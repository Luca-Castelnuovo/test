<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login();

switch ($_GET['type']) {
    case 'add':
        $title = 'Add Project';
        $content = ['<label for="add">Project Name</label>', '<input placeholder="Project Name" type="text" name="project_name" class="text validate" id="add" autocomplete="off" autofocus>'];
        $button_text = 'Add my project';
        break;
    case 'delete':
        $title = 'Delete Project';
        $content = ['<label for="delete">To confirm type `delete`</label>', '<input type="text" name="project_delete" class="text validate" id="delete" autocomplete="off" autofocus>'];
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
    <form class="login">
        <input type="hidden" name="type" value="<?= clean_data($_GET['type']) ?>"/>
        <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>"/>
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
        <p class="title"><?= $title ?></p>
        <div class="input-field">
            <?php foreach ($content as $row) {echo $row;} ?>
        </div>
        <button id="submit"><span class="state"><?= $button_text ?></span></button>
    </form>
</div>
<?php footer('projects'); ?>
</body>

</html>
