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
    Log::debug('wxAuthTest');

    if(!Auth::guest()){
        return ;
    }

    $weChatClient = new \Cooper\Wechat\WeChatClient($_ENV['WECHAT_PAY_APP_ID'], $_ENV['WECHAT_PAY_APP_SECRET']);

    //查找是否有code，如果没有，则获取code
    $code = Input::get('code');
    if(empty($code)){
        $url = $weChatClient->getOAuthConnectUri(URL::current(), '', 'snsapi_userinfo');
        return Redirect::to($url);
    }

    //获取accessToken和OpenId
    $accessTokenResult = $weChatClient->getAccessTokenByCode($code);
    $openid = $accessTokenResult['openid'];
    Log::debug($openid);

    //获取用户信息
    $accessToken = $accessTokenResult['access_token'];
    $userInfo = $weChatClient->getUserInfoByAuth($accessToken, $openid);

    //存储微信用户信息
    $profiles = array_only($userInfo, array('nickname', 'open_id', 'sex', 'province','city','country','headimgurl', 'privilege'));
    isset($profiles['privilege']) && $profiles['privilege'] = json_encode($profiles['privilege']);
    $userProfile = weChatUserProfile::find($openid);
    if($userProfile){
        $userProfile->update($profiles);
    }else{
        weChatUserProfile::create(array_add($profiles, 'openid' , $openid));
    }

    Log::debug($userInfo);

    //如果用户存在，则直接登录
    $appId = APP_WE_CHAT;
    $app = RelationUserApp::whereAppUserId($openid)->whereAppId($appId)->first();
    if($app){
        Auth::loginUsingId($app->user_id, true);
    }else{
        return Redirect::to("/mobile_bond?app_user_id=$openid&app_id=$appId");
    }
});

Validator::extend('telephone_not_exist', function($attribute, $value, $parameters){
    return !User::whereTelephone($value)->exists();
});

Validator::extend('user_auth', function ($attribute, $value, $parameters) {
    $password = $parameters[0];
    if ($password) {
        return Auth::attempt(array('nickname' => $value, 'password' => $password), true) ||
        Auth::attempt(array('telephone' => $value, 'password' => $password), true);
    }
    return false;
});

Validator::extend('user_unique', function($attribute, $value, $parameters){
    //参数1为user_id，有用于修改的情况
    $user = User::where($attribute, '=', $value)->first();
    if($user){
        return (count($parameters) > 0) ? ($user->user_id == $parameters[0]) : false;
    }
    return true;
});