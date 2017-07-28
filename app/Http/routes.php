<?php

Route::get('users/upload', 'UserController@upload');

Route::get('users/pdf', 'UserController@pdf');

Route::get('users/pdfdown', 'UserController@pdfdown');


/**************************************/

Route::get('auth/login', 'Auth\AuthController@getLogin');

Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/register', 'Auth\AuthController@getRegister');

Route::post('auth/register', 'Auth\AuthController@postRegister');


Route::get('/', 'TaskController@list');

Route::post('/task', 'TaskController@task');

Route::delete('/task/id/{id}', 'TaskController@delete');




/**************************************/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function ($api) {

        /*User controller*/
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

        $api->post('users/doupload', 'UserController@doupload');

        $api->get('users/arraytest', 'UserController@arraytest');

    });
});

