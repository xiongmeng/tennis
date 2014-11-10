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
require_once 'route/finance.php';
require_once 'route/weChat.php';
require_once 'route/notify.php';
require_once 'route/user.php';
require_once 'route/hall.php';
require_once 'route/instantOrder.php';
require_once 'route/reserveOrder.php';
require_once 'route/area.php';

Route::get('/', function () {
    if (Auth::check()) {
        $roles = user_roles();
        $role = $roles[0]->role_id;
        if ($role == 1) {
            return Redirect::to('hall_on_sale');
        }
        if ($role == 2) {
            return Redirect::to('/reserve_order_mgr/book_pending');
        }
        if ($role == 3) {
            return Redirect::to('/set_receive_sms_telephone');
        }
    } else {
        return View::make('layout')->nest('content', 'login');
    }
});

View::creator('format.header', function ($view) {
    if (Auth::check()) {
        $user = Auth::getUser();
        $roles = user_roles();
        $roleIds = array();
        foreach ($roles as $role) {
            $roleIds[] = $role->role_id;
        }

        $headers = Config::get('acl.headers');
        $allRolesHeaders = Config::get('acl.roles_headers');
        $acl = array();
        foreach ($allRolesHeaders as $roleId => $rolesHeaders) {
            if (in_array($roleId, $roleIds)) {
                $acl = array_merge($acl, $rolesHeaders);
            }
        }
        $view->with('headers', $headers)->with('acl', $acl)->with('user', $user);
    }
});

View::creator('layout', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.header')->nest('copyright', 'format.copyright')->nest('breadcrumb', 'format.breadcrumb');
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
        //返回登录前页面
        $url = URL::previous();
        return $redirect = Redirect::to($url);

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