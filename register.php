<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (isset($_GET['reset'])) {
    reset_();
}

if ($_SESSION['auth_code_valid']) {
    $js = 'register';
    $title = 'Register';
    $button_text = 'Submit';
    $type = 'register';
    $content = ['<input placeholder="Username" type="text" name="user_name" autocomplete="off" class="text" id="username" autofocus> <i class="fa fa-user"></i>', '<input placeholder="Password" type="password" name="user_password" autocomplete="off" class="text" id="password"> <i class="fa fa-key"></i>'];
} else {
    $js = 'auth';
    $title = 'Invite Code';
    $button_text = 'Check Invite Code';
    $content = ['<input placeholder="Invite Code" type="text" name="auth_code" id="auth_code" autocomplete="off" class="text" autofocus value="' . clean_data($_GET['auth_code']) . '"> <i class="fa fa-barcode"></i>'];
}

?>


<!DOCTYPE html>

<html lang="en">

<?php head($title); ?>

<body>
<div class="wrapper">
    <form class="login">
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
        <p class="title"><?= $title ?></p>
        <?php foreach ($content as $row) {
            echo $row;
        } ?>
        <button id="submit"><i class="spinner"></i> <span class="state"><?= $button_text ?></span></button>
    </form>
</div>
<?php footer($js); ?>
</body>

</html>
