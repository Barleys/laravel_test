<?php

Route::get('/', function () {
    return view('welcome');
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->group(['prefix' => 'v1'], function($api){

        $api->post('user/login', 'App\Http\Controllers\UserController@login');

        $api->post('user/signup', 'App\Http\Controllers\UserController@signup');

        $api->post('user/auth', 'App\Http\Controllers\UserController@auth');

        $api->get('user/query/id/{id}', 'App\Http\Controllers\UserController@query');

        $api->post('user/search', 'App\Http\Controllers\UserController@search');

        $api->get('users/show', 'App\Http\Controllers\UserController@show');

        $api->get('users/middlewares/id/{id}', ['middleware' => 'old', 'App\Http\Controllers\UserController@middlewares']);

//        $api->get('users/middlewares/id/{id}', 'App\Http\Controllers\UserController@middlewares');

    });

});

