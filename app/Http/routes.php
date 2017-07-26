<?php

Route::get('users/list', 'UserController@list');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function($api){

        $api->post('user/login', 'UserController@login');

        $api->post('user/signup', 'UserController@signup');

        $api->post('user/auth', 'UserController@auth');

        $api->get('user/query/id/{id}', 'UserController@query');

        $api->post('user/search', 'UserController@search');

        $api->get('users/show', 'UserController@show');

        $api->get('users/middlewares/id/{id}', 'UserController@middlewares');

        $api->get('users/pays', 'UserController@pays');

        $api->get('articles/tags', 'UserController@tags');

        $api->get('users/info/id/{id}', 'UserController@info')->middleware(['old']);

        $api->get('users/nodes', 'UserController@nodes');

        $api->get('users/tq', 'UserController@tq');

        $api->get('users/level', 'UserController@level');

    });
});

