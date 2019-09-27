<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['json.response']], function () {

    // public routes
    Route::post('/login', 'Api\AuthController@login');

    // private routes
    Route::middleware('auth:api')->group(function () {
        //users route
        Route::get('/users', 'Api\UsersController@index')->middleware('users.controller.permission.check');
        Route::post('/users', 'Api\UsersController@store')->middleware('users.controller.permission.check');
        Route::patch('/users/{id}', 'Api\UsersController@update')->middleware('users.controller.permission.check');
        Route::delete('/users/{id}', 'Api\UsersController@destroy')->middleware('users.controller.permission.check');

        //projects route
        Route::get('/projects', 'Api\ProjectsController@index')->middleware('projects.controller.permission.check');
        Route::post('/projects', 'Api\ProjectsController@store')->middleware('projects.controller.permission.check');
        Route::patch('/projects/{id}', 'Api\ProjectsController@update')->middleware('projects.controller.permission.check');
        Route::delete('/projects/{id}', 'Api\ProjectsController@destroy')->middleware('projects.controller.permission.check');

        //logout
        Route::get('/logout', 'Api\AuthController@logout');
    });

});