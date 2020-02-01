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

Route::group(['middleware' => ['auth', 'role:parent']], function () use ($router) {
  Route::post('register-child', 'AuthController@registerChild');
  Route::post('family/create', 'FamilyController@store');
  Route::put('family/update', 'FamilyController@update');
  //STATUS
  Route::put('users/status/{id}', 'UserController@updateStatus');
  Route::put('users/timer/{id}', 'UserController@updateTimer');
});
Route::group(['middleware' => 'auth'], function () use ($router) {
  //PROFILE
  Route::get('profile', 'UserController@profile');
  Route::put('profile/edit', 'UserController@editProfile');
  Route::put('profile/photo', 'UserController@editPhoto');
  Route::put('profile/password', 'UserController@editPassword');
  //GCM TOKEN
  Route::put('users/storegcmtoken', 'UserController@storeGCMToken');
  //GET USER
  Route::get('users', 'UserController@allUsers');
  Route::get('users/{id}', 'UserController@singleUser');
  //EVENT
  Route::get('event', 'EventController@index');
  Route::post('event/create', 'EventController@store');
  Route::put('event/update/{id}', 'EventController@update');
  Route::delete('event/delete/{id}', 'EventController@destroy');
  //LOCATION
  Route::put('location/update', 'LocationController@update');
  Route::get('location/{id}', 'LocationController@getLocation');
  //MESSAGE
  Route::get('messages', 'ChatsController@fetchMessages');
  Route::post('messages/create', 'ChatsController@sendMessage');
});
Route::post('register', 'AuthController@register'); // register parent
Route::post('login', 'AuthController@login'); // login all
