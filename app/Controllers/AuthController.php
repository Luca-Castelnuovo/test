<?php

namespace App\Controllers\Auth;

use DB;
use Exception;
use App\Controllers\Controller;
use App\Helpers\JWTHelper;
use App\Helpers\StateHelper;
use App\Helpers\SessionHelper;
use Zend\Diactoros\ServerRequest;


class AuthController extends Controller
{

    /**
     * Initialize the OAuth provider
     * 
     * @return Google
     */
    private function provider()
    {
        return new Google([
            'clientId'     => config('auth.google.client_id'),
            'clientSecret' => config('auth.google.client_secret'),
            'redirectUri' => config('app.url') . '/auth/google/callback'
        ]);
    }

    /**
     * Redirect to OAuth
     *
     * @param ServerRequest $request
     * 
     * @return RedirectResponse
     */
    public function request(ServerRequest $request)
    {
        $provider = $this->provider();

        $authUrl = $provider->getAuthorizationUrl();
        StateHelper::set($provider->getState());

        return $this->redirect($authUrl);
    }

    /**
     * Callback for OAuth
     *
     * @param ServerRequest $request
     * 
     * @return RedirectResponse
     */
    public function callback(ServerRequest $request)
    {
        $state = $request->getQueryParams()['state'];
        $code = $request->getQueryParams()['code'];

        if (!StateHelper::valid($state)) {
            return $this->logout('State is invalid');
        }

        try {
            $provider = $this->provider();
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $data = $provider->getResourceOwner($token);
            $id = StringHelper::escape($data->toArray()['sub']);
        } catch (Exception $e) {
            return $this->logout("Error: {$e}");
        }

        return $this->login(['google' => $id]);
    }

    /**
     * Create session
     * 
     * @param string|null $next
     *
     * @return RedirectResponse
     */
    public function login($user_where)
    {
        $user = DB::get('users', ['id', 'active [Bool]', 'admin [Bool]'], $user_where);

        if (!$user) {
            return $this->logout('Account not found!');
        }

        if (!$user['active']) {
            return $this->logout('Your account has been deactivated! Contact the administrator');
        }

        $return_to = SessionHelper::get('return_to');

        SessionHelper::destroy();
        SessionHelper::set('id', $user['id']);
        SessionHelper::set('admin', $user['admin']);
        SessionHelper::set('ip', $_SERVER['REMOTE_ADDR']);
        SessionHelper::set('last_activity', time());

        if ($return_to) {
            return $this->redirect($return_to);
        }

        return $this->redirect('/dashboard');
    }

    /**
     * Destroy session
     * 
     * @param string $message optional
     *
     * @return RedirectResponse
     */
    public function logout($message = 'You have been logged out!')
    {
        SessionHelper::destroy();

        if ($message) {
            $jwt = JWTHelper::create('message', [
                'message' => $message
            ]);

            return $this->redirect("/?msg={$jwt}");
        }

        return $this->redirect('/');
    }
}
