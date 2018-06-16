<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/core.php");

if (isset($_GET['logout'])) {
    ses_clear();
    $_SESSION['success'] = 'Logout successful';
    header('Location: index');
    exit;
}

if (isset($_GET['api'])) {
    ses_clear();
    $_SESSION['error'] = 'API token invalid!';
    header('Location: index');
    exit;
}

if (isset($_GET['delete'])) {
    ses_clear();
    $_SESSION['success'] = 'Account Deleted!';
    header('Location: index');
    exit;
}

if (isset($_GET['404'])) {
    ses_clear();
    $_SESSION['error'] = 'That page doesn\'t exist!';
    header('Location: index');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $api_response = api_request('add', $_POST['email'], $_POST['password']);
    if ($api_response['status']) {
        $_SESSION['success'] = 'Account Created!';
    } else {
        $_SESSION['error'] = 'Account Not Created!';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $api_response = api_request('verify', $_POST['email'], $_POST['password']);
    if ($api_response['status']) {
        $_SESSION['logged_in'] = 1;
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];
        header('Location: profile');
        exit;
    } else {
        $_SESSION['error'] = 'Login Unsuccessful!';
    }
}

?>
    <!DOCTYPE html>
    <html lang="en">

    <?php head('Login'); ?>

    <body>
        <div class="login-form">
            <form action="index" method="post">
                <h2 class="text-center">Login</h2>
                <?php alert(); ?>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" required="required" autocomplete="off" name="email" autofocus>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" required="required" autocomplete="off" name="password">
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6">
                            <button class="btn btn-primary btn-lg btn-block" type="submit" name="register">Register</button>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6">
                            <button class="btn btn-primary btn-lg btn-block" type="submit" name="login">Login</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>

    </html>
