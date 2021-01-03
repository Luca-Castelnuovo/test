<?php

use CQ\Middleware\JSON;
use CQ\Middleware\RateLimit;
use CQ\Middleware\Session;
use CQ\Routing\Middleware;
use CQ\Routing\Route;

Route::$router = $router->get();
Middleware::$router = $router->get();

Route::get('/', 'GeneralController@index');
Route::get('/error/{code}', 'GeneralController@error');

Middleware::create(['prefix' => '/auth'], function () {
    Route::get('/request', 'AuthController@request');
    Route::get('/callback', 'AuthController@callback');

    Route::get('/request/device', 'AuthController@requestDevice');
    Route::post('/callback/device', 'AuthController@callbackDevice');

    Route::get('/logout', 'AuthController@logout');
});

Middleware::create(['middleware' => [Session::class]], function () {
    Route::get('/dashboard', 'UserController@dashboard');
});

Middleware::create(['prefix' => '/project', 'middleware' => [Session::class]], function () {
    Route::post('', 'ProjectsController@create', JSON::class);
    Route::get('/{id}', 'ProjectsController@view');
    Route::delete('/{id}', 'ProjectsController@delete');
});

Middleware::create(['prefix' => '/file', 'middleware' => [Session::class]], function () {
    Route::post('/{project_id}', 'FilesController@create', JSON::class);
    Route::get('/{id}', 'FilesController@view');
    Route::put('/{id}', 'FilesController@update', [JSON::class, RateLimit::class]);
    Route::delete('/{id}', 'FilesController@delete');
});
