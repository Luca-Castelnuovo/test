<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (isset($_GET['reset'])) {
    reset_();
}

if ($_SESSION['auth_code_valid']) {
    $type = 'register';
    $content = ['<div class="input-field"><label for="username">Username</label><input type="text" name="user_name" class="text validate" id="username" autocomplete="off" required autofocus></div><div class="input-field"><label for="password">Password</label><input type="password" name="user_password" class="text validate" id="password" autocomplete="off" required></div>'];
} else {
    $type = 'invite_code';
    $content = ['<div class="input-field"><label for="auth_code">Invite Code</label><input type="text" name="auth_code" class="text validate" id="auth_code" autocomplete="off" value="' . clean_data($_GET['auth_code']) . ' required autofocus></div>'];
}

?>

<!DOCTYPE html>

<html lang="en">

<?php head('Register'); ?>

<body>
<div class="wrapper">
    <form class="login">
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>"/>
        <input type="hidden" name="type" value="<?= $type ?>"/>
        <p class="title"><?= $title ?></p>
        <?php foreach ($content as $row) {echo $row;} ?>
        <button id="submit"><i class="spinner"></i> <span class="state">Submit</span></button>
    </form>
</div>
<?php footer('register'); ?>
</body>

</html>
