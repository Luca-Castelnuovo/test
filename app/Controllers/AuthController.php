<?php

namespace App\Controllers;

use Exception;
use App\Controllers\Controller;
use App\Helpers\StringHelper;
use App\Helpers\SessionHelper;
use lucacastelnuovo\AppsClient\AppsClient;
use Zend\Diactoros\ServerRequest;

class AuthController extends Controller
{

    /**
     * Initialize the provider
     * 
     * @return AppsClient
     */
    private function provider()
    {
        return new AppsClient([
            'app_id' => config('app.id'),
            'app_url'     => config('app.url')
        ]);
    }

    /**
     * Redirect to authorization portal
     * 
     * @return RedirectResponse
     */
    public function request()
    {
        $provider = $this->provider();
        $authUrl = $provider->getAuthorizationUrl();

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
        $code = $request->getQueryParams()['code'];
        $provider = $this->provider();

        try {
            // $data = $provider->getData($code, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']); // TODO: enable for production
            $data = $provider->getData($code, '86.87.160.103', $_SERVER['HTTP_USER_AGENT']);
        } catch (Exception $e) {
            return $this->logout("Error: {$e->getMessage()}");
        }

        $id = StringHelper::escape($data->sub); // Value is used in DB calls

        return $this->login($id, $data->variant, $data->exp);
    }

    /**
     * Create session
     * 
     * @param string $id
     * @param string $variant
     * @param string $expires
     *
     * @return RedirectResponse
     */
    public function login($id, $variant, $expires)
    {
        SessionHelper::destroy();
        SessionHelper::set('id', $id);
        SessionHelper::set('variant', $variant);
        SessionHelper::set('ip', $_SERVER['REMOTE_ADDR']);
        SessionHelper::set('expires', $expires);

        // TODO: create folder

        return $this->redirect('/dashboard');
    }

    /**
     * Destroy session
     * 
     * @param string $msg optional
     *
     * @return RedirectResponse
     */
    public function logout($msg = 'You have been logged out!')
    {
        SessionHelper::destroy();

        if ($msg) {
            return $this->redirect("/?msg={$msg}");
        }

        return $this->redirect('/');
    }
}
