<?php

namespace App\Controllers;

use CQ\Controllers\Auth;
use CQ\Helpers\Request;
use CQ\Helpers\Session;
use CQ\Helpers\Folder;

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
        if (!Folder::exists($user_path)) {
            Folder::create($user_path);

            $projectsController = new ProjectsController;
            $projectsController->create((object) [
                'data' => (object) [
                    'name' => 'Hello World',
                ],
            ]);
        }

        return $this->redirect('/dashboard');
    }
}
