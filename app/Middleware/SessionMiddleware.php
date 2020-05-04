<?php

namespace App\Middleware;

use App\Helpers\AuthHelper;
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\JsonResponse;

class SessionMiddleware implements Middleware
{
    /**
     * Validate PHP session.
     *
     * @param Request $request
     * @param $next
     *
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, $next)
    {
        if (!AuthHelper::valid()) {
            if ($request->isJSON) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Session expired',
                    'data' => ['redirect' => "/auth/request"]
                ], 403);
            }

            return new RedirectResponse("/auth/request");
        }

        return $next($request);
    }
}
