<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('show', function(){
    return view('form');
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->group(['prefix' => 'v1'], function($api){

        $api->post('user/login', 'App\Http\Controllers\UserController@login');

        $api->post('user/signup', 'App\Http\Controllers\UserController@signup');

        $api->post('user/auth', 'App\Http\Controllers\UserController@auth');

        $api->get('user/query/id/{id}', 'App\Http\Controllers\UserController@query');

        $api->post('user/search', 'App\Http\Controllers\UserController@search');

    });

});

