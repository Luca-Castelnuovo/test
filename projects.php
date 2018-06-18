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

<?php head($title); ?>

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
