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
        if (auth($_GET['auth_code'], 'register', 1)) {success();} else {error(2);}
        break;

    case 'register':
        if ($_SESSION['auth_code_id_confirm'] === 1) {
            unset($_SESSION['auth_code_id_confirm']);
            if (empty($_GET['user_name']) || empty($_GET['user_password'])) {error(6);}
            $user_name = clean_data($_GET['user_name']);
            $user_password = clean_data($_GET['user_password']);
            sql("INSERT INTO users (user_name, user_password) VALUES ('{$user_name}', '{$user_password}')");
            success();
        } else {
            error(1);
        }
        break;

    default:
        error(0);
        break;
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

function auth($input_code, $input_type, $input_code_id)
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
            sql("UPDATE codes SET used='0',ip='{$auth_ip}' WHERE code='{$input_code}'");
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
