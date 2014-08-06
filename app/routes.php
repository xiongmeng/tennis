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

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::getUser();
        $user_id = $user['user_id'];
        $roles = user::find($user_id)->roles;
        $role = $roles[0]->role_id;
        if($role == 1){
            return Redirect::to('instant_order_on_sale');
        }
        if($role == 2){
            return Redirect::to('instant_order_mgr');
        }
        if($role == 3){
            return Redirect::to('order_court_manage?hall_id=&court_id=');
        }
    }
    else {
        return View::make('layout')->nest('content', 'login');
    }
});

View::creator('format.top', function ($view) {
    if (Auth::check()) {
        $user = Auth::getUser();
    } else {
        $user = array();
    }

    $view->with('user', $user);
});

View::creator('format.header', function ($view) {
    if (Auth::check()) {
        $user = Auth::getUser();
        $user_id = $user['user_id'];
        $roles = user::find($user_id)->roles;
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
        $data = array('headers' => $headers, 'acl' => $acl);
    } else {
        $data = array();
    }
    $view->with('data', $data);
});

View::creator('layout', function(\Illuminate\View\View $view)
{
    $view->nest('top','format.top')->nest('header', 'format.header')
         ->nest('copyright', 'format.copyright');
});

Route::get('/home', function () {

    return View::make('layout')->nest('content', 'home');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return Redirect::to('/');
    }
    else {
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

Route::get('/instant_order_mgr', function () {
    $queries = Input::all();
    $instantModel = new InstantOrder();
    $array = array();
    $array['state'] = 'draft';
    $instants = $instantModel->search($queries,$array);

    $states = Config::get('state.data');

    return View::make('layout')->nest('content','instantOrder.order_mgr',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states));
});

Route::get('/instant_order_buyer', function () {
    $queries = Input::all();
    if (Auth::check()) {
        $user = Auth::getUser();
        $userID = $user['user_id'];
    }
    $instantModel = new InstantOrder();
    $array['buyer'] = $userID;
    $instants = $instantModel->search($queries, $array);
    $states = Config::get('state.data');

    return View::make('layout')->nest('content','instantOrder.order_buyer',
        array('instants' => $instants, 'states' => $states, 'userID' => $userID, 'queries' => $queries));
});

Route::get('/instant_order_on_sale',array('as' => 'order_on_sale', function () {
    $queries = Input::all();
    $instantModel = new InstantOrder();
    $array['expire_time'] = time();
    $instants = $instantModel->search($queries, $array);

    if (Auth::check()) {
        $user = Auth::getUser();
        $userID = $user['user_id'];
    }
    $states = Config::get('state.data');
    return View::make('layout')->nest('content','instantOrder.order_on_sale',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'userID' => $userID));
}));

Route::get('/instant_order_seller', function () {
    $queries = Input::all();
    $instantModel = new InstantOrder();
    if (Auth::check()) {
        $user = Auth::getUser();
        $userID = $user['user_id'];
    }
    $array['seller'] = $userID;
    $instants = $instantModel->search($queries, $array);
    $states = Config::get('state.data');
    return View::make('layout')->nest('content','instantOrder.order_seller',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'userID' => $userID));
});

Route::get('/order_court_manage', function () {
    if (Auth::check()) {
        $instantModel = new InstantOrder();
        $user = Auth::getUser();
        $hallID = Input::get('hall_id');
        $courtID = Input::get('court_id');
        $halls = $user->Halls;
        if (!$hallID && count($halls) > 0) {
            $hallID = $halls[0]->id;
        }
        $courts = Court::where('hall_id', '=', $hallID)->get();
        if (!$courtID && count($courts) > 0) {
            $courtID = $courts[0]->id;
        }
        $states = Config::get('state.data');
        $instants = $instantModel->where('hall_id', '=', $hallID)->where('court_id', '=', $courtID)->where('event_date', '>=', date('Y-m-d'))->get();
        $dates = array(date('Y-m-d 00:00:00'), date('Y-m-d 00:00:00', strtotime('+1 day')), date('Y-m-d 00:00:00', strtotime('+2 day')), date('Y-m-d 00:00:00', strtotime('+3 day')), date('Y-m-d 00:00:00', strtotime('+4 day')));

        return View::make('layout')->nest('content','instantOrder.order_court_manage',
            array('instants' => $instants, 'states' => $states, 'courts' => $courts, 'halls' => $halls, 'dates' => $dates));
    }
});

Route::get('/fsm-operate/{id?}/{operate?}', function ($id, $operate) {
    if (Auth::check()) {
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $fsm->apply($operate);
        $url = URL::previous();
        return $redirect = Redirect::to($url);
    }
});

Route::get('/billing_buyer', array('before' => 'auth', function(){
    $queries = Input::all();

    !isset($queries['purpose']) && $queries['purpose']=\Sports\Constant\Finance::PURPOSE_ACCOUNT;

    $defaultQueries = array();
    $user = Auth::getUser();
    if($user instanceof User){
        $defaultQueries['user_id'] = $user->user_id;
    }

    $billingStagingModel = new BillingStaging();
    $billingStagings = $billingStagingModel->search(array_merge($queries, $defaultQueries));

    return View::make('layout')->nest('content', 'user.billing_buyer',
        array('queries' => $queries, 'billingStagings' => $billingStagings));
}));

Route::get('/billing_seller', array('before' => 'auth', function(){
    $queries = Input::all();

    !isset($queries['purpose']) && $queries['purpose']=\Sports\Constant\Finance::PURPOSE_ACCOUNT;

    $defaultQueries = array();
    $user = Auth::getUser();
    if($user instanceof User){
        $defaultQueries['user_id'] = $user->user_id;
    }

    $billingStagingModel = new BillingStaging();
    $billingStagings = $billingStagingModel->search(array_merge($queries, $defaultQueries));

    return View::make('layout')->nest('content', 'user.billing_seller',
        array('queries' => $queries, 'billingStagings' => $billingStagings));
}));