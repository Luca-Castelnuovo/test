<?php

use App\Middleware\JSONMiddleware;
use App\Middleware\SessionMiddleware;
use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Zend\Diactoros\Response\RedirectResponse;

$router = new Router('', 'App\Controllers');
$router->define('httpcode', '[0-9]+');

$router->get('/', 'GeneralController@index');
$router->get('/error/{httpcode}', 'GeneralController@error');

$router->group(['prefix' => '/auth'], function (Router $router) {
    $router->get('/request', 'AuthController@request');
    $router->get('/callback', 'AuthController@callback');
    $router->get('/logout', 'AuthController@logout');
});

$router->group(['middleware' => SessionMiddleware::class], function (Router $router) {
    $router->get('/dashboard', 'UserController@dashboard');
});

$router->group(['prefix' => '/projects', 'middleware' => SessionMiddleware::class], function (Router $router) {
    $router->post('', 'ProjectsController@create', JSONMiddleware::class);
    $router->get('/{id}', 'ProjectsController@view');
    $router->delete('/{id}', 'ProjectsController@delete');
});

$router->group(['prefix' => '/files', 'middleware' => SessionMiddleware::class], function (Router $router) {
    $router->post('', 'FilesController@create', JSONMiddleware::class);
    $router->get('/{id}', 'FilesController@view');
    // $router->put('/{id}', 'FilesController@update', JSONMiddleware::class);
    $router->delete('/{id}', 'FilesController@delete');
});

try {
    $router->dispatch();
} catch (RouteNotFoundException $e) {
    $router->getPublisher()->publish(new RedirectResponse('/error/404', 404));
} catch (Throwable $e) {
    $router->getPublisher()->publish(new RedirectResponse("/error/500?e={$e}", 500));
}
