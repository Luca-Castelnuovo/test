<?php

session_start();

$config = parse_ini_file('/var/www/test/config.ini');
$mysqli = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

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
        logout();
    }

    if (!(hash_equals($_SESSION['token'], $post_token))) {
        logout();
    } else {
        unset($_SESSION['token']);
    }
}

//validate_csrf
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

//check if user has been logged in
function login()
{
    if (!$_SESSION['logged_in']) {
        logout();
    }

    //check if account is active
    if (!$_SESSION['user_active']) {
        logout();
    }

    //auto logout after 10min no activity
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
        logout();
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
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), “”, time() - 3600, “/”);
    }
    $_SESSION = array();
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
    echo '<h2 class="uppercase">Projects:</h2><table>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $project_id = $row["id"];
            $project_name = $row["project_name"];
            echo "<td class='inline'><a class='dropdown-trigger btn' href='?project={$project_id}' data-target='{$project_id}'>{$project_name}</a></td>";
            echo "<ul id='{$project_id}' class='dropdown-content'>
                    <li><a href='?project={$project_id}'>files</a></li>
                    <li><a href='projects?type=delete&id={$project_id}'>delete</a></li>
                </ul>";
        }
    }
    echo '</tr></table><br><a href="/?logout">Log Out</a><a href="projects?type=add" class="fl-rt">New Project</a>';
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
    echo '<h2 class="uppercase">' . $project_name . ':</h2><table><tr><td class="inline">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $file_id = $row["id"];
            $project_id = $row["project_id"];
            $file = $row["file"];
            echo "<td class='inline'><a class='dropdown-trigger btn' href='?project={$file_id}' data-target='{$file_id}'>{$file}</a></td>";
            echo "<ul id='{$file_id}' class='dropdown-content'>
                    <li><a href='users/{$_SESSION['user_name']}/{$project_name}/{$file}' target='_blank'>view</a></li>
                    <li><a href='files?type=edit&id={$file_id}&project_id={$project_id}'>edit</a></li>
                    <li><a href='files?type=delete&id={$file_id}&project_id={$project_id}'>delete</a></li>
                </ul>";
        }
    }
    echo '</tr></table><br><a href="home">Go Back</a><a href="files?type=add&project_id=' . $project_id . '" class="fl-rt">New File</a>';
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
                if (is_dir($dir . "/" . $object))
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        rmdir($dir);
    }
}
