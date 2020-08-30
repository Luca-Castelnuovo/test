<?php

namespace App\Controllers;

use CQ\Controllers\Auth;
use CQ\Helpers\Request;
use CQ\Helpers\Session;

class AuthController extends Auth
{
    /**
     * Create session.
     *
     * @param object $user
     * @param string $expires_at
     *
     * @return Redirect
     */
    public function login($user, $expires_at)
    {
        $return_to = Session::get('return_to');

        Session::destroy();

        // User Info
        Session::set('user', [
            'id' => $user['sub'],
            'roles' => $user['roles'],
            'email' => $user['email'],
            'name' => $user['preferred_username'],
        ]);

        // Auth Info
        Session::set('session', [
            'expires_at' => $expires_at,
            'created_at' => time(),
            'ip' => Request::ip(),
        ]);

        // Activity Info
        Session::set('last_activity', time());

        if ($return_to) {
            return $this->redirect($return_to);
        }

        $user_path = "users/{$user['sub']}";
        if (!is_dir($user_path)) {
            mkdir($user_path, 0770);
        }

        return $this->redirect('/dashboard');
    }
}
