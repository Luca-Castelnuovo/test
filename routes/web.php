<?php

use CQ\Routing\Route;
use CQ\Routing\Middleware;
use CQ\Middleware\JSON;
use CQ\Middleware\Session;
use CQ\Middleware\RateLimit;

Route::$router = $router->get();
Middleware::$router = $router->get();

Route::get('/', 'GeneralController@index');
Route::get('/error/{code}', 'GeneralController@error');

Middleware::create(['prefix' => '/auth'], function () {
    Route::get('/request', 'AuthController@request');
    Route::get('/callback', 'AuthController@callback');
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
