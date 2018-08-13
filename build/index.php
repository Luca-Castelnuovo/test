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
            <div class="loader"><i class="spinner"></i></div>
            <div class="content">
                <p class="title">Log in</p>
                <div class="input-field">
                    <label for="username">Username</label>
                    <input type="text" name="user_name" class="text validate" id="username" autocomplete="off" required autofocus>
                </div>
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" name="user_password" class="text validate" id="password" autocomplete="off" required>
                </div>
                <input type="hidden" name="CSRFtoken" value="<?= csrf_gen(); ?>" />
                <button id="submit"><i class="spinner"></i> <span class="state">Log in</span></button>
            </div>
        </form>
    </div>
    <?php footer('login'); ?>
</body>

</html>
