<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
if (isset($_GET['logout'])) {
    logout();
} ?>
<!DOCTYPE html>

<html lang="en">

<?php head('Log In'); ?>

<body>
    <div class="wrapper">
        <form class="login" method="post">
            <p class="title">Log in</p>
            <div class="input-field">
                <label for="username">Username</label>
                <input type="text" name="user_name" class="text validate" id="username" autocomplete="off" autofocus>
            </div>
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" name="user_password" class="text validate" id="password" autocomplete="off">
            </div>
            <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>" />
            <button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
        </form>
    </div>
    <?php footer('login'); ?>

<label for="file_name">File Name</label>
<input type="text" name="file_name" autocomplete="off" class="text" autofocus>
<p>Please choose a file type</p>
<p><label><input name="file_lang" type="radio" value="html" checked> <span>HTML</span></label></p>
<p><label><input name="file_lang" type="radio" value="css"> <span>CSS</span></label></p>
<p><label><input name="file_lang" type="radio" value="js"> <span>JavaScript</span></label></p>

</body>

</html>
