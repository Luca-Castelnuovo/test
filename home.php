<?php

require $_SERVER['DOCUMENT_ROOT'] . '/includes/init.php';

loggedin();

if (isset($_GET['id']) && isset($_GET['delete_project']) && isset($_GET['CSRFtoken'])) {
    projects_delete($_SESSION['id'], $_GET['id'], $_GET['CSRFtoken']);
}

page_header('Home');

?>

<?php if (!isset($_GET['id'])) { ?>
<div class="row">
    <?php projects_list($_SESSION['id']); ?>
</div>
<?php } else { ?>
<div class="row">
    <?php projects_info($_SESSION['id'], $_GET['id']); ?>
</div>
<?php } ?>

<?= page_footer(); ?>
