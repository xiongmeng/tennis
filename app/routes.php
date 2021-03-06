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
require_once 'route/zxTest.php';
require_once 'route/finance.php';
require_once 'route/weChat.php';
require_once 'route/notify.php';
require_once 'route/user.php';
require_once 'route/hall.php';
require_once 'route/instantOrder.php';
require_once 'route/reserve.php';
require_once 'route/area.php';
require_once 'route/seeking.php';

Route::get('/', function () {
    if (Auth::check()) {
        $role = current_role();
        $roles = Config::get('acl.roles');

        return Redirect::to($roles[$role]['home']);
    } else {
        Layout::setHighlightHeader('nav_用户_首页');
        return View::make('layout')->nest('content', 'login');
    }
});

View::creator('format.header', function (\Illuminate\View\View $view) {
    $currentRole = current_role();

    $headers = Config::get('acl.headers');
    $allRolesHeaders = Config::get('acl.roles_headers');
    $acl = $allRolesHeaders[$currentRole];
    $arguments = array('acl' => $acl, 'headers' => $headers);

    if (Auth::check()) {
        $arguments['user'] = $user = Auth::getUser();
        $arguments['roles'] = $user->roles;
    }

    $view->with($arguments);
});

View::creator('layout', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.header')
        ->nest('copyright', 'format.copyright')->nest('breadcrumb', 'format.breadcrumb');
});

Route::get('/home', function () {

    return View::make('layout')->nest('content', 'home');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return Redirect::to('/');
    } else {
        return View::make('layout')->nest('content', 'login');
    }
});

Route::get('/logout', function () {
    Auth::logout();
    return Redirect::to('login');
});

Route::post('/logining', function () {
    $nickname = Input::get('nickname');
    $password = Input::get('password');
    $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
    $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
    if ($isNickLog | $isTeleLog) {
        $user = Auth::getUser();
        $roles = $user->roles;

        $url = Session::get(SESSION_KEY_LOGIN_CALLBACK, '/');
        $currentRole = current_role();
        if($currentRole != ROLE_VISITOR){
            return Redirect::to($url);
        }else if(count($roles) < 2){
            //设置合适的角色
            $activeRole = ROLE_USER;
            if(count($roles) > 0){
                $activeRole = $roles[0]->role_id;
            }
            current_role($activeRole);

            return Redirect::to($url);
        }else{
            return Redirect::to('/role/active');
        }
    } else {
        echo '登陆失败';
    }
});

Route::any('/weixin_access', 'WeiXinController@index');

Route::get('/setMenu',function(){
    $menu = Config::get('/packages/cooper/wechat/menu.WeChatMenu');
    $client = new \Cooper\Wechat\WeChatClient();
    return ' ' . $client->setMenu($menu[0]);
});