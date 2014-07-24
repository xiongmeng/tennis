<?php

Route::group(array('prefix' => 'fj'), function(){

    Route::get('/', function(){
        return 'I am fj';
    });

    View::creator('format.top', function($view)
    {
        if(Auth::check()){
            $user = Auth::getUser();}
        else{$user = array();}

        $view->with('user',$user);
    });

    Route::get('/homel', function(){
        return $view =View::make('home')->nest('top','format.top');
});
    Route::get('/logout', function(){
        Auth::logout();
        return $view =View::make('home')->nest('top','format.top');
    });
    Route::get('/login', function(){
        if(Auth::check()){
            return $view =View::make('home')->nest('top','format.top');
        }
        else{
        return $view = View::make('login')->nest('header', 'format.header');
        }
    });
    Route::post('/logining', function(){
        $nickname = Input::get('nickname');
        $password = Input::get('password');
        $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
        $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
        if ($isNickLog | $isTeleLog){
            return $view =View::make('home')->nest('top','format.top');

        }
        else echo '登陆失败';
    });
});
Route::get('/user', function(){
    if(Auth::check()){
        $user = Auth::getUser();
        echo $user['telephone'];

    };
});

