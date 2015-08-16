<?php

Route::post('oauth/token', function() {
  return Response::json(Authorizer::issueAccessToken());
});

Route::get('/', function() {
  return "API Documentation will go here... ";
});

/**
 * Publicly Avaialable Routes.
 * TODO - Please lock this with client-id and secret.
 */
Route::group(array('prefix' => 'api'), function()
{
  Route::resource('users', 'UsersController', ['only'=>['store']]);
  Route::post('send_reset_code', 'PasswordResetController@send_reset_code');
  Route::post('verify_code', 'PasswordResetController@verify_code');
  Route::post('reset_password', 'PasswordResetController@reset_password');
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
  Route::resource('projects.environments', 'EnvironmentsController');
  Route::resource('environments.vars', 'EnvironmentVarsController');
  Route::resource('items', 'ItemsController');
  Route::resource('postman', 'PostmanController', ['only' =>['store']]);

});

/**
 * Super Admin Endpoints.
 */
Route::group(array('prefix' => 'sa','before' => 'oauth|isSuperAdmin'), function()
{
  Route::resource('analytics', 'AnalyticsController', ['only'=>['index']]);
});

Route::filter('isSuperAdmin', function()
{
  $resource_owner_id = Authorizer::getResourceOwnerId();
  $user = User::find($resource_owner_id);
  if($user->email != 'chinmay@chinmay.ca')
  {
    App::abort(401, 'YOU ARE NOT A SUPER USER, YET.');
  }
});
