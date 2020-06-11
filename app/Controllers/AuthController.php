<?php

namespace App\Controllers;

use CQ\Helpers\Session;
use CQ\Helpers\Request;
use CQ\Controllers\Auth;

class AuthController extends Auth
{
    /**
     * Create session
     * 
     * @param string $id
     * @param string $variant
     * @param string $expires
     *
     * @return Redirect
     */
    public function login($id, $variant, $expires)
    {
        $return_to = Session::get('return_to');

        Session::destroy();
        Session::set('id', $id);
        Session::set('variant', $variant);
        Session::set('ip', Request::ip());
        Session::set('expires', $expires);

        if ($return_to) {
            return $this->redirect($return_to);
        }

        $user_path = "users/{$id}";
        if (!is_dir($user_path)) {
            mkdir($user_path, 0770);
        }

        return $this->redirect('/dashboard');
    }
}
