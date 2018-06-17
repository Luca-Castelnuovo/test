<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (csrf_val_ajax(clean_data($_GET['CSRFtoken']))) {
    error(5);
}

switch ($_GET['type']) {
    case 'login':
        $user_name = clean_data($_GET['username']);
        $user_password = clean_data($_GET['password']);

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
                error(4);
            }
        } else {
            error(3);
        }
        break;

    case 'register_auth':
        if (auth($_GET['auth_code'], 'register', 1, 0)) {$_SESSION['input_code'] = $_GET['auth_code']; success();} else {error(2);}
        break;

    case 'register':
        if ($_SESSION['auth_code_valid'] && $_SESSION['auth_code_id'] === 1) {
            $input_code = $_SESSION['input_code'];
            if (empty($_GET['user_name']) || empty($_GET['user_password'])) {error(6);}
            $user_name = clean_data($_GET['user_name']);
            $user_password = password_hash(clean_data($_GET['user_password']), PASSWORD_BCRYPT);
            $check_existing_user = sql("SELECT id FROM users WHERE user_name='{$user_name}'");
            if ($check_existing_user->num_rows > 0) {error(9);}
            sql("UPDATE codes SET used='1',user='{$user_name}' WHERE code='{$input_code}'");
            sql("INSERT INTO users (user_name, user_password) VALUES ('{$user_name}', '{$user_password}')");
            unset($_SESSION['input_code']);
            unset($_SESSION['auth_code_id']);
            unset($_SESSION['auth_code_valid']);
            mkdir("users/{$user_name}", 0770);
            success();
        } else {
            error(1);
        }
        break;

    case 'invite':
        if ($_SESSION['user_type']) {
            $user_email = clean_data($_GET['user_email']);
            $code = gen(256);
            $created = date("d/m/Y h:i:s");
            sql("INSERT INTO codes (code, valid, created, type) VALUES ('{$code}', '7', '{$created}', 'register')");
            $_SESSION['invite_response'] = '<a href="https://test.lucacastelnuovo.nl/register?auth_code=' . $code . '">Copy Link</a><br><br><a href="/home">Go Back</a><a href="/admin" style="float:right;">Send Another Invite</a>';
            success();
        } else {
            error(1);
        }
        break;

    case 'projects':
        $project_id = clean_data($_GET['project_id']);
        $project_name = clean_data($_GET['project_name']);

        switch($_GET['project_type']) {
            case 'add':
                if (sql("INSERT INTO projects (owner_id, project_name) VALUES ('{$_SESSION['user_id']}', '{$project_name}')")) {
                    mkdir("users/{$_SESSION['user_name']}/{$project_name}");
                    success();
                } else {
                    error(0);
                }

                break;
            case 'delete':
                $projects = sql("SELECT project_name FROM projects WHERE id='{$project_id}'AND owner_id='{$_SESSION['user_id']}'", true);
                $project_name = $projects['project_name'];
                if (sql("DELETE FROM projects WHERE id='{$project_id}' AND owner_id='{$_SESSION['user_id']}'")) {
                    if(!empty($project_name)) {
                        rrmdir("users/{$_SESSION['user_name']}/{$project_name}");
                    }
                    header('Location: /home');
                } else {
                    error(0);
                }
                break;
            default:
                error(0);
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

function auth($input_code, $input_type, $input_code_id, $deactive_immediatly = 1)
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
            $_SESSION['auth_code_valid'] = true;
            $_SESSION['auth_code_id'] = $input_code_id;
            return true;
        } else {
            return false;
        }
    } else {
       return false;
    }
}
