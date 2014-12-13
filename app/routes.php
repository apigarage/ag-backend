<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::post('oauth/token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

/**
 * Publicly Avaialable Routes.
 * TODO - Please lock this with client-id and secret. 
 */
Route::group(array('prefix' => 'api'), function()
{
    Route::resource('users', 'UserController', 
                array('only' => array('store')));

    Route::get('users/check_email_availibility', 'UserController@check_email_availibility');
    Route::get('employers/all', 'EmployerController@index');
});

/**
 * API Blocked Routes. 
 */
Route::group(array('prefix' => 'api','before' => 'oauth'), function()
{
    Route::get('/', function()
    {
        return Response::json( array('success' => 'Yey, you have access to the secure part of the API now') );
    });

    Route::resource('users', 'UsersController', ['only'=>['show','update']]);
    Route::resource('collections', 'CollectionsController');
    Route::resource('items', 'ItemsController');

});
