<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use Zend\Diactoros\ServerRequest;

class GeneralController extends Controller
{
    /**
     * Login screen
     * 
     * @param ServerRequest $request
     * 
     * @return RedirectResponse|HtmlResponse
     */
    public function index(ServerRequest $request)
    {
        $msg = $request->getQueryParams()['msg'] ?: '';
        $code = $request->getQueryParams()['code'] ?: '';

        if ($code) {
            return $this->redirect("/auth/callback?code={$code}");
        }

        if ($msg) {
            switch ($msg) {
                case 'logout':
                    $msg = 'You have been logged out!';
                    break;

                case 'token':
                    $msg = 'Invalid authentication!';
                    break;

                default:
                    $msg = '';
                    break;
            }
        }

        return $this->respond('index.twig', [
            'message' => $msg,
            'logged_in' => AuthHelper::valid()
        ]);
    }

    /**
     * Error screen
     * 
     * @param string $httpcode
     *
     * @return HtmlResponse
     */
    public function error($httpcode)
    {
        switch ($httpcode) {
            case '403':
                $short_message = 'Oops! Access denied';
                $message = 'Access to this page is forbidden';
                break;
            case '404':
                $short_message = 'Oops! Page not found';
                $message = 'We are sorry, but the page you requested was not found';
                break;
            case '500':
                $short_message = 'Oops! Server error';
                $message = 'We are experiencing some technical issues';
                break;

            default:
                $short_message = 'Oops! Unknown Error';
                $message = 'Unknown error occured';
                $httpcode = 400;
                break;
        }

        return $this->respond('error.twig', [
            'code' => $httpcode,
            'short_message' => $short_message,
            'message' => $message
        ], $httpcode);
    }
}
