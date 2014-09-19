<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});


App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {

    if (Input::get('app_user_id') && Input::get('app_id')) {
        $appUserID = Input::get('app_user_id');
        $appID = Input::get('app_id');
        $app = RelationUserApp::where('app_user_id','=',$appUserID)->first();
        if (!$app) {
            Auth::logout();
            return Redirect::to(url_wrapper('bond'));
        } else {
            $user = User::find($app['user_id']);
            if ($user instanceof User) {
                Auth::login($user);
            }
        }
    }
    else{
        if (Auth::guest()) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Redirect::guest('login');
            }
        }
    }
});


Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

/*
|--------------------------------------------------------------------------
|  WeiXin Filter
|--------------------------------------------------------------------------
|

|
*/

Route::filter('weixin', function () {
        $appUserID = Input::get('app_user_id');
        $appID = Input::get('app_id');
        $app = RelationUserApp::where('app_user_id','=',$appUserID)->first();
        if (!$app) {
            Auth::logout();
            return Redirect::to(url_wrapper('/mobile_bond'));
        } else {
            $user = User::find($app['user_id']);
            if ($user instanceof User) {
                Auth::login($user);
            }
        }
});

Route::filter('weChatAuth', function(){
    Log::debug('weChat');
    $weChatClient = new \Cooper\Wechat\WeChatClient('wxd443d79e0d69a2b8', '7d7fcb8090fb61366bf4c4534e4f66dc');
    $code = Input::get('code');
    $accessTokenResult = $weChatClient->getAccessTokenByCode($code);
    $userInfo = $weChatClient->getUserInfoByAuth($accessTokenResult['access_token'], $accessTokenResult['openid']);


});