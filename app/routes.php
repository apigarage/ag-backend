<?php

Route::post('oauth/token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

/**
 * Publicly Avaialable Routes.
 * TODO - Please lock this with client-id and secret. 
 */
Route::group(array('prefix' => 'api'), function()
{
    Route::resource('users', 'UsersController', ['only'=>['store']]);
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
    Route::resource('projects', 'ProjectsController');
    Route::resource('collections', 'CollectionsController');
    Route::resource('environments', 'EnvironmentsController');
    Route::resource('environments.vars', 'EnvironmentVarsController');
    Route::resource('items', 'ItemsController');

});
