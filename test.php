<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");
if (isset($_GET['logout'])) {
    logout();
} ?>
<!DOCTYPE html>

<html lang="en">

<?php head('Log In'); ?>

<body>
    <div class="wrapper">
        <div class="row">
            <form class="col s12 login">
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="password" type="text" class="validate text" name="user_name" autocomplete="off">
                        <label for="password">Username</label>
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock</i>
                        <input id="username" type="password" class="validate text" name="user_password" autocomplete="off">
                        <label for="password">Password</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php footer('login'); ?>
</body>

</html>
