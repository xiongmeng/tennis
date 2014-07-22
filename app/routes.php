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

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/home', function(){
    return 'home';
});

Route::get('/test', function(){
   return 'test2';
});

Route::get('/test-push-github', function(){
    return 'test-push-github';
});

Route::get('/login', function(){
    Auth::attempt(array('nickname' => 'rener', 'password' => 123456));
});