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

require_once 'route/xmTest.php';
require_once 'route/fjTest.php';

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

Route::get('/login/{nickname?}/{password?}', function($nickname, $password){
    $isLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
    echo $isLog ? '成功' : '失败';
});

Route::get('/user', function(){
    if(Auth::check()){
        $user = Auth::getUser();
        if($user instanceof Eloquent){
            return $user->getAttributes();
        }
    };
});