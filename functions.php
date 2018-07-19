<?php

session_start();

$config = parse_ini_file('/var/www/test/config.ini');
$mysqli = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

function clean_data($data)
{
    global $mysqli;
    $data = $mysqli->escape_string($data);
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

function gen($length)
{
    $length = $length / 2;
    return bin2hex(random_bytes($length));
}

function csrf_gen()
{
    if (isset($_SESSION['token'])) {
        return $_SESSION['token'];
    } else {
        $_SESSION['token'] = gen(32);
        return $_SESSION['token'];
    }
}

function csrf_val($post_token)
{
    if (!isset($_SESSION['token'])) {
        logout();
    }

    if (!(hash_equals($_SESSION['token'], $post_token))) {
        logout();
    } else {
        unset($_SESSION['token']);
    }
}

function csrf_val_ajax($token)
{
    if (!isset($_SESSION['token'])) {
        return true;
    }

    if (!(hash_equals($_SESSION['token'], $token))) {
        return true;
    } else {
        unset($_SESSION['token']);
        return false;
    }
}

function login()
{
    if (!$_SESSION['logged_in']) {
        logout();
    }

    $active = sql("SELECT user_active FROM users WHERE id='{$_SESSION['user_id']}'", true);
    $active = $active['user_active'];
    if (!$active) {
        logout();
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
        logout();
    } else {
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > 600) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }

    if ($_SESSION['ip'] != ip()) {
        logout();
    }
}

function login_admin()
{
    login();

    if (!$_SESSION['user_type']) {
        logout();
    }
}

function logout()
{
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /');
    exit;
}

function reset_()
{
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), “”, time() - 3600, “ / ”);
    }
    $_SESSION = array();
    session_destroy();
    $trimmed = str_replace('.php', '', $_SERVER['PHP_SELF']);
    header("Location: {$trimmed}");
}

function my_projects()
{
    $result = sql("SELECT * FROM projects WHERE owner_id='{$_SESSION['user_id']}'");
    echo '<h2 class="uppercase">Projects</h2><table>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $project_id = $row["id"];
            $project_name = $row["project_name"];
            echo "<td class='inline'><a class='dropdown-trigger btn' href='/home/{$project_id}' data-target='{$project_id}'>{$project_name}</a></td>";
            echo "<ul id='{$project_id}' class='dropdown-content'>
                    <li><a href='/home/{$project_id}'>files</a></li>
                    <li><a href='/projects/delete/{$project_id}'>delete</a></li>
                </ul>";
        }
    }
    if ($_SESSION['user_type']) {
        $admin = '      <a href="/admin">Admin</a>';
    }
    echo '</tr></table><br><a href="/?logout">Log Out</a> ' . $admin . '  <a href="/projects/add" class="fl-rt">New Project</a>';
}

function my_project($project_id)
{
    $project = sql("SELECT id FROM projects WHERE id='{$project_id}' AND owner_id='{$_SESSION['user_id']}'");
    if ($project->num_rows == 0) {
        header('Location: /home');
        exit;
    }

    $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
    $project_name = $projects['project_name'];

    $result = sql("SELECT * FROM files WHERE project_id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'");
    echo '<h2 class="uppercase">' . $project_name . '</h2><table><tr>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $file_id = $row["id"];
            $project_id = $row["project_id"];
            $file = $row["file"];
            echo "<td class='inline'><a class='dropdown-trigger btn' href='/{$file_id}' data-target='{$file_id}'>{$file}</a></td>";
            echo "<ul id='{$file_id}' class='dropdown-content'>
                    <li><a href='/users/{$_SESSION['user_name']}/{$project_name}/{$file}' target='_blank'>view</a></li>
                    <li><a href='/files/edit/{$project_id}/{$file_id}'>edit</a></li>
                    <li><a href='/files/delete/{$project_id}/{$file_id}'>delete</a></li>
                </ul>";
        }
    }
    echo '</tr></table><br><a href="/home">Go Back</a><a href="/files/add/' . $project_id . '" class="fl-rt">New File</a>';
}

function sql($query, $return_value = false)
{
    global $mysqli;
    $result = $mysqli->query($query);
    if ($return_value) {
        $array = $result->fetch_assoc();
        return $array;
    } else {
        return $result;
    }
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    rrmdir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

function head($title, $displayHead = true)
{
    if ($displayHead) {
        echo '<head>';
    }
    echo '
    <title>' . $title . '</title>
    <meta charset=utf-8>
    <meta content="ie=edge" http-equiv=x-ua-compatible>
    <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name=viewport>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.lucacastelnuovo.nl/css/vanilla/test.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans:400,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">';
    if ($displayHead) {
        echo '</head>';
    }
}

function footer($specific_js = false)
{
    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.lucacastelnuovo.nl/js/vanilla/loader.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script src="https://cdn.lucacastelnuovo.nl/php/background.php?background=7"></script>';
    if ($specific_js) {
        echo "
    <script src= '/js/{$specific_js}.js'></script>";
    }
    echo '
    <script>setTimeout(stopLoading, 100);</script>';
}
