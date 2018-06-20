<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
login_admin();

$back_button = true;
$csrftoken = csrf_gen();

if (isset($_SESSION['invite_response'])) {
    $type = 'code_response';
    $content = ["<a class='dropdown-trigger btn' href='https://test.lucacastelnuovo.nl/register?auth_code={$_SESSION['invite_response']}'>Registration link</a>"];
    unset($_SESSION['invite_response']);
    $title = 'Registration Cod';
} elseif (isset($_GET['login_log'])) {
    $type = 'login_log';
    $log_file_content = file_get_contents('login.txt');
    $content = ['<textarea rows="30" cols="60">' . $log_file_content . '</textarea><br>'];
    $title = 'Login Logs';
} elseif (isset($_GET['users'])) {
    $type = 'users';
    $result = sql("SELECT * FROM users ORDER BY user_name ASC");
    if ($result->num_rows > 0) {
        $content = [];
        while ($row = $result->fetch_assoc()) {
            $user_id = $row["id"];
            $user_name = $row["user_name"];
            $user_type = $row["user_type"];
            $user_active = $row["user_active"];
            if($user_active) {$user_status = 'Deactivate';} else {$user_status = 'Activate';}
            array_push($content,"<a class='dropdown-trigger btn' href='#' data-target='{$user_id}'>{$user_name}</a>","<ul id='{$user_id}' class='dropdown-content'><li><a href='process?type=admin&admin_type=active&user_id={$user_id}&CSRFtoken={$csrftoken}'>{$user_status}</a></li><li><a onclick='return confirm(\"Are you sure?\")' href='process?type=admin&admin_type=delete&user_id={$user_id}&CSRFtoken={$csrftoken}'>Delete</a></li></ul>");
        }
    }
    $title = 'All Users';
} else {
    $type = 'default';
    $content = ["<a class='dropdown-trigger btn' href='#' id='submit'>Generate Invite Code</a><a class='dropdown-trigger btn' href='?users'>Users</a><a class='dropdown-trigger btn' href='?login_log'>Login Log</a><a class='dropdown-trigger btn' href='/home'>Back</a>"];
    $back_button = false;
    $title = 'Admin Panel';
}

if ($back_button) {
    array_push($content,"<a class='dropdown-trigger btn' href='/admin'>Back</a>");
}

?>
<!DOCTYPE html>

<html lang="en">

<?php head('Admin Panel'); ?>

<body>
<div class="wrapper">
    <form class="login pd-20 <?php if($type == 'login_log') {echo 'admin log';}?>">
        <p class="title"><?= $title ?></p>
        <input type="hidden" name="CSRFtoken" value="<?= $csrftoken ?>"/>
        <input type="hidden" name="id" value="<?= clean_data($_GET['id']) ?>" />
        <div class="inline">
            <?php foreach($content as $row) {echo $row;} ?>
        </div>
    </form>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="js/invite.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>$('.dropdown-trigger').dropdown();</script>
</body>

</html>
