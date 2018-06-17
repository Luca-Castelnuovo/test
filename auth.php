<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/functions.php");

if (csrf_val_ajax(clean_data($_GET['CSRFtoken']))) {
    error();
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
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_active'] = $user['user_active'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = 1;

                $text = date('Y-m-d H:i:s') . '	:	' . $_SESSION['ip'] . '	:	' . $_SESSION['user_name'] . PHP_EOL;
                $file = fopen("login.txt", "a+");
                fwrite($file, $text);

                success();
            } else {
                error();
            }
        } else {
            error();
        }
        break;
    case 'register_auth':
        $code = clean_data($_GET['auth_code']);
        $auth = sql("SELECT valid,created,type,used FROM codes WHERE code='{$code}'");

        if ($auth->num_rows == 0) {
            error();
        } elseif ($auth->num_rows == 1) {
            $auth = $auth->fetch_assoc();
            $created = $auth["created"];
            $valid = $auth["valid"];
            $type = $auth["type"];
            $used = $auth["used"];
            if (!($created >= $valid) && !$used && $type == 'register') {
                $ip = ip();
                sql("UPDATE codes SET used='1',ip='$ip' WHERE code='$code'");
                success();
            } else {
                error();
            }
        } else {
            error();
        }


        break;
    case 'register':
        $user_name = clean_data($_GET['user_name']);
        $user_password = clean_data($_GET['user_password']);
        sql("INSERT INTO users (user_name, user_password) VALUES ('{$user_name}', '{$user_password}')");
        break;

    default:
        break;
}

function error()
{
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
