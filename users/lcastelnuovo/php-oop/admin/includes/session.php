<?php

class Session
{
    private $logged_in = false;
    public $user_id;

    function __construct()
    {
        session_start();
        $this->check_the_login();
    }

    private function check_session_set()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function login($user)
    {
        if ($user) {
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->logged_in = true;
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = false;
    }

    private function check_the_login()
    {
        if ($this->check_session_set()) {
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = true;
        } else {
            unset($this->user_id);
            $this->logged_in = false;
        }
    }

    public function is_logged_in()
    {
        return $this->logged_in;
    }
}

$session = new Session;
