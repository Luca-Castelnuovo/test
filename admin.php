<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login_admin();

$back_button = true;

if (isset($_SESSION['invite_response'])) {
    $content = echo "<a class='dropdown-trigger btn' href='https://test.lucacastelnuovo.nl/register?auth_code={$_SESSION['invite_response']}'>Registration link</a>";
    unset($_SESSION['invite_response']);
} elseif (isset($_GET['login_log'])) {
    $log_file_content = file_get_contents('login.txt');
    $content = '<pre>' . $log_file_content . '</pre>';
} elseif (isset($_GET['users'])) {
    //query all users
    //give a overview of users
    //option per user (deactivate/activate, delete, view projects)
} else {
    $content = "<a class='dropdown-trigger btn' href='#' id='submit'>Generate Invite Code</a><a class='dropdown-trigger btn' href='?users'>Users</a><a class='dropdown-trigger btn' href='?login_log'>Login Log</a><a class='dropdown-trigger btn' href='/home'>Back</a>";
    $back_button = false;
}

if ($back_button) {
    $content = $content . "<a class='dropdown-trigger btn' href='/admin'>Back</a>";
}

?>
<!DOCTYPE html>

<html lang="en">

<?php head('Admin Panel'); ?>

<body>
<div class="wrapper">
    <form class="login pd-20">
        <p class="title">Admin Panel</p>
        <input type="hidden" name="CSRFtoken" value="<?= csrf_gen() ?>"/>
        <div class="inline">
			<?= $content ?>

        </div>
    </form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/invite.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</body>

</html>
