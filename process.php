<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (csrf_val_ajax(clean_data($_GET['CSRFtoken']))) {
    error(14);
}

switch ($_GET['type']) {
    case 'login':
        if (empty($_GET['user_name']) || empty($_GET['user_password'])) {
            error(10);
        }
        $user_name = strtolower(clean_data($_GET['user_name']));
        $user_password = clean_data($_GET['user_password']);

        $user = sql("SELECT * FROM users WHERE user_name='{$user_name}'");

        if ($user->num_rows != 0) {
            $user = $user->fetch_assoc();
            if (password_verify($user_password, $user['user_password'])) {
                $_SESSION['ip'] = ip();
                $_SESSION['logged_in'] = 1;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_active'] = $user['user_active'];
                $_SESSION['user_type'] = $user['user_type'];

                $text = date('Y-m-d H:i:s') . '	:	' . $_SESSION['ip'] . '	:	' . $_SESSION['user_name'] . PHP_EOL;
                $file = fopen("login.txt", "a+");
                fwrite($file, $text);

                success();
            } else {
                error(13);
            }
        } else {
            error(12);
        }
        break;

    case 'register':
        switch ($_GET['register_type']) {
            case 'invite_code':
                if (empty($_GET['auth_code'])) {error(10);}
                if (auth($_GET['auth_code'], 'register', 0)) {
                    $_SESSION['input_code'] = $_GET['auth_code'];
                    $_SESSION['auth_code_valid'] = true;
                    success();
                } else {
                    error(11);
                }
                break;
            case 'register':
                if ($_SESSION['auth_code_valid']) {
                    $input_code = $_SESSION['input_code'];
                    if (empty($_GET['user_name']) || empty($_GET['user_password'])) {
                        error(10);
                    }
                    $user_name = strtolower(clean_data($_GET['user_name']));
                    $user_password = password_hash(clean_data($_GET['user_password']), PASSWORD_BCRYPT);
                    $check_existing_user = sql("SELECT id FROM users WHERE user_name='{$user_name}'");
                    if ($check_existing_user->num_rows > 0) {
                        error(9);
                    }
                    sql("UPDATE codes SET used='1',user='{$user_name}' WHERE code='{$input_code}'");
                    sql("INSERT INTO users (user_name, user_password) VALUES ('{$user_name}', '{$user_password}')");
                    unset($_SESSION['input_code']);
                    unset($_SESSION['auth_code_valid']);
                    mkdir("users/{$user_name}", 0770);
                    success();
                } else {
                    error(8);
                }
                break;
            default:
                error(15);
        }
        break;

    case 'admin':
        $user_id = clean_data($_GET['user_id']);
        switch ($_GET['admin_type']) {
            case 'invite':
                if ($_SESSION['user_type']) {
                    $code = gen(256);
                    $created = date("d/m/Y h:i:s");
                    sql("INSERT INTO codes (code, valid, created, type) VALUES ('{$code}', '7', '{$created}', 'register')");
                    $_SESSION['invite_response'] = $code;
                    success();
                } else {
                    error(7);
                }
                break;
            case 'active':
                if (empty($_GET['user_id'])) {
                    error(10);
                }
                if ($_SESSION['user_type']) {
                    $user = sql("SELECT user_active FROM users WHERE id='{$user_id}'", true);
                    if ($user['user_active']) {
                        sql("UPDATE users SET user_active='0' WHERE id='{$user_id}'");
                    } else {
                        sql("UPDATE users SET user_active='1' WHERE id='{$user_id}'");
                    }
                    header('Location: /admin?users');
                    exit;
                } else {
                    error(16);
                }
                break;
            case 'delete':
                if (empty($_GET['user_id'])) {
                    error(10);
                }
                if ($_SESSION['user_type']) {
                    sql("DELETE FROM users WHERE id='{$user_id}'");
                    header('Location: /admin?users');
                    exit;
                } else {
                    error(17);
                }
                break;
            default:
                error(15);
        }
        break;

    case 'projects':
        switch ($_GET['project_type']) {
            case 'add':
                if (empty($_GET['project_name'])) {
                    error(10);
                }
                $project_owned = sql("SELECT id FROM projects WHERE owner_id='{$_SESSION['user_id']}'");
                if ($project_owned->num_rows > 10) {error(10);}

                $project_name = strtolower(clean_data($_GET['project_name']));
                if (sql("INSERT INTO projects (owner_id, project_name) VALUES ('{$_SESSION['user_id']}', '{$project_name}')")) {
                    mkdir("users/{$_SESSION['user_name']}/{$project_name}");
                    success();
                } else {
                    error(0);
                }

                break;
            case 'delete':
                if (empty($_GET['project_id']) || empty($_GET['project_delete'])) {
                    error(10);
                }
                $project_id = clean_data($_GET['project_id']);
                $project_delete = clean_data($_GET['project_delete']);
                $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                $project_name = $projects['project_name'];
                if ($project_delete == 'delete') {
                    if (sql("DELETE FROM projects WHERE id='{$project_id}' AND owner_id='{$_SESSION['user_id']}'")) {
                        if (!empty($project_name)) {
                            rrmdir("users/{$_SESSION['user_name']}/{$project_name}");
                        }
                        success();
                    } else {
                        error(0);
                    }
                } else {
                    error(0);
                }
                break;
            default:
                error(0);
        }
        break;

    case 'files':
        $project_id = clean_data($_GET['project_id']);
        $file_id = clean_data($_GET['file_id']);
        $file_name = strtolower(clean_data($_GET['file_name']));

        switch ($_GET['file_type']) {
            case 'add':
                if (empty($_GET['file_lang']) || empty($_GET['project_id']) || empty($_GET['file_name'])) {
                    error(10);
                }
                $files_owned = sql("SELECT id FROM files WHERE owner_id='{$_SESSION['user_id']}'");
                if ($files_owned->num_rows > 50) {error(10);}
                
                $file_lang = clean_data($_GET['file_lang']);
                switch ($file_lang) {
                    case 'html':
                        $file_name_lang = $file_name . '.html';
                        break;
                    case 'css':
                        $file_name_lang = $file_name . '.css';
                        break;
                    case 'js':
                        $file_name_lang = $file_name . '.js';
                        break;
                    default:
                        logout();
                }

                $project = sql("SELECT id FROM projects WHERE id='{$project_id}' AND owner_id='{$_SESSION['user_id']}'");
                if ($project->num_rows == 0) {
                    header('Location: /home');
                    exit;
                }

                if (sql("INSERT INTO files (owner_id, project_id, file) VALUES ('{$_SESSION['user_id']}', '{$project_id}', '{$file_name_lang}')")) {
                    $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                    if (!empty($projects['project_name'])) {
                        fopen("users/{$_SESSION['user_name']}/{$projects['project_name']}/{$file_name_lang}", "w");
                        fclose("users/{$_SESSION['user_name']}/{$projects['project_name']}/{$file_name_lang}");
                        success();
                    } else {
                        error(6);
                    }
                } else {
                    error(5);
                }
                break;
            case 'edit':
                if (empty($_GET['project_id']) || empty($_GET['file_id'])) {
                    error(10);
                }
                $file_content = $_POST['file_content'] . PHP_EOL;
                $files = sql("SELECT file FROM files WHERE id='{$file_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                $file_path_full = "users/{$_SESSION['user_name']}/{$projects['project_name']}/{$files['file']}";
                $file_open = fopen($file_path_full, "w");
                $file_content = "\xEF\xBB\xBF" . $file_content;
                if (fwrite($file_open, $file_content)) {
                    fclose($file_path_full);
                    success();
                } else {
                    fclose($file_path_full);
                    error(4);
                }
                break;
            case 'delete':
                if (empty($_GET['project_id']) || empty($_GET['file_id'])) {
                    error(10);
                }
                $file_delete = clean_data($_GET['file_delete']);
                $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                $files = sql("SELECT file FROM files WHERE id='{$file_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                if ($file_delete == 'delete') {
                    if (sql("DELETE FROM files WHERE id='{$file_id}' AND owner_id='{$_SESSION['user_id']}'")) {
                        unlink("users/{$_SESSION['user_name']}/{$projects['project_name']}/{$files['file']}");
                        success();
                    } else {
                        error(3);
                    }
                } else {
                    error(2);
                }
                break;
            default:
                error(1);
        }
        break;

    default:
        error(0);
}

function error($error_code = null)
{
    //$out = ["status" => false, "error_code" => $error_code];
    $out = ["status" => false];
    echo json_encode($out);
    exit;
}

function success()
{
    $out = ["status" => true];
    echo json_encode($out);
    exit;
}

function auth($input_code, $input_type, $deactive_immediatly = 1)
{
    $input_code = clean_data($input_code);
    $auth = sql("SELECT valid,created,type,used FROM codes WHERE code='{$input_code}'");
    if ($auth->num_rows == 0) {
        return false;
    } elseif ($auth->num_rows == 1) {
        $auth = $auth->fetch_assoc();
        $auth_created = $auth["created"];
        $auth_valid = $auth["valid"];
        $auth_type = $auth["type"];
        $auth_used = $auth["used"];
        $auth_ip = ip();
        if (!($auth_created >= $auth_valid) && !$auth_used && $auth_type == $input_type) {
            sql("UPDATE codes SET used='{$deactive_immediatly}',ip='{$auth_ip}' WHERE code='{$input_code}'");
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
