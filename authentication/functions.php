<?php

$config = parse_ini_file('/var/www/test/config.ini');
$mysqli = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);


//display alert
function alert()
{
    if (isset($_SESSION['alert'])) {
        $error = $_SESSION['alert'];
        print'<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\'; window.location.search += \'&clear\';">&times;</span><b><p style="margin-bottom: 0;">' . $error . '</p></b></div>';
        unset($_SESSION['alert']);
        if (isset($_GET["clear"])) {
            unset($_SESSION['alert']);
            unset($error);
            header('Location:' . $_SERVER['REQUEST_URI']);
        }
    }
}


//clean user data
function clean_data($data)
{
    global $mysqli;
    $data = $mysqli->escape_string($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

//get user ip
function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

//random gen
function gen($length)
{
    $length = $length / 2;
    return bin2hex(random_bytes($length));
}

//generate_csrf
function csrf_gen()
{
    if (isset($_SESSION['token'])) {
        return $_SESSION['token'];
    } else {
        $_SESSION['token'] = gen(32);
        return $_SESSION['token'];
    }
}

//validate_csrf
function csrf_val($post_token)
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['alert'] = 'CSRF error!';
        header('Location: /authentication/');
        exit;
    }

    if (!(hash_equals($_SESSION['token'], $post_token))) {
        $_SESSION['alert'] = 'CSRF error!';
        header('Location: /authentication/');
        exit;
    } else {
        unset($_SESSION['token']);
    }
}

//check if user has been logged in
function login()
{
    if ($_SESSION['logged_in'] != 1) {
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        $_SESSION['alert'] = 'Please Log In!';
        header("location: /authentication/");
        exit;
    }

    //check if account is active
    if ($_SESSION['active'] != 1) {
        $_SESSION['alert'] = 'Your Account is not active or is temporarily disables';
        header('Location: /authentication/');
        exit;
    }

    //auto logout after 10min no activity
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
        $_SESSION['alert'] = 'Your session is expired!';
        header('Location: /authentication/');
        exit;
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    //regenerate session id (sec against session stealing)
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > 600) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }

    //check if session is stolen
    if ($_SESSION['ip'] != ip_rem()) {
        $_SESSION['alert'] = 'Hack attempt detected!';
        header('Location: /authentication/');
        exit;
    }
}
