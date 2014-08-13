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
        $roles = $user->roles;
        $role = $roles[0]->role_id;
        if ($role == 1) {
            return Redirect::to('instant_order_on_sale');
        }
        if ($role == 2) {
            return Redirect::to('instant_order_mgr');
        }
        if ($role == 3) {
            return Redirect::to('order_court_manage');
        }
    } else {
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
        $roles = $user->roles;
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
        $data = array('headers' => array());
    }
    $view->with('data', $data);
});

View::creator('layout', function (\Illuminate\View\View $view) {
    $view->nest('top', 'format.top')->nest('header', 'format.header')
        ->nest('copyright', 'format.copyright');
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

Route::get('/instant_order_mgr', array('before' => 'auth', function () {
    $queries = Input::all();
    $instantModel = new InstantOrder();

    $queries['state'] = array_keys(
        array_except(Config::get('fsm.instant_order.states'), array('draft', 'waste')));

    $instants = $instantModel->search($queries);

    $states = Config::get('state.data');

    return View::make('layout')->nest('content', 'instantOrder.order_mgr',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states));
}));

Route::get('/instant_order_buyer', array('before' => 'auth', function () {
    $queries = Input::all();
    $user = Auth::getUser();
    $userID = $user['user_id'];
    $queries['buyer'] = $userID;
    $instantModel = new InstantOrder();
    $instants = $instantModel->search($queries);
    $states = Config::get('state.data');

    return View::make('layout')->nest('content', 'instantOrder.order_buyer',
        array('instants' => $instants, 'states' => $states, 'userID' => $userID, 'queries' => $queries));
}));

Route::get('/instant_order_on_sale', array('before' => 'auth', function () {
    $queries = Input::all();
    $queries['expire_time_start'] = time();

    $instantModel = new InstantOrder();
    $instants = $instantModel->search($queries);

    $user = Auth::getUser();
    $userID = $user['user_id'];

    $states = Config::get('state.data');
    return View::make('layout')->nest('content', 'instantOrder.order_on_sale',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'userID' => $userID));
}));

Route::get('/instant_order_seller', array('before' => 'auth', function () {
    $queries = Input::all();
    $instantModel = new InstantOrder();

    $user = Auth::getUser();
    $userID = $user['user_id'];

    $queries['seller'] = $userID;
    $queries['state'] = 'finish';

    $instants = $instantModel->search($queries);
    $states = Config::get('state.data');
    return View::make('layout')->nest('content', 'instantOrder.order_seller',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'userID' => $userID));
}));

Route::get('/order_court_manage', array('before' => 'auth', function () {
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

    $instants = InstantOrder::orderBy('start_hour', 'asc')->where('hall_id', '=', $hallID)
        ->where('court_id', '=', $courtID)->where('event_date', '>=', date('Y-m-d'))->get();

    $formattedInstants = array();
    foreach ($instants as $instant) {
        !isset($formattedInstants[$instant->event_date]) && $formattedInstants[$instant->event_date] = array();
        $formattedInstants[$instant->event_date][$instant->start_hour] = $instant;
    }

    $dates = array();
    for ($i = 0; $i < 7; $i++) {
        $dates[] = date('Y-m-d 00:00:00', strtotime("+$i day"));
    }

    $weekdayOption = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');

    $states = Config::get('state.data');
    return View::make('layout')->nest('content', 'instantOrder.order_court_manage', array('instants' => $instants,
        'states' => $states, 'courts' => $courts, 'halls' => $halls, 'dates' => $dates, 'hallID' => $hallID,
        'courtID' => $courtID, 'weekdayOption' => $weekdayOption, 'formattedInstants' => $formattedInstants));

}));

Route::get('/fsm-operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $instantOrder = InstantOrder::findOrFail($id);
    $fsm = new InstantOrderFsm($instantOrder);
    $fsm->apply($operate);
    $url = URL::previous();
    return $redirect = Redirect::to($url);

}));

Route::get('/billing_buyer/{curTab?}', array('before' => 'auth', function ($curTab) {
    $tabs = array(
        'account_balance' => array(
            'label' => '账户收支明细',
            'url' => '/billing_buyer/account_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_ACCOUNT,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
        'points_balance' => array(
            'label' => '积分明细',
            'url' => '/billing_buyer/points_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_POINTS,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
    );

    $queries = Input::all();

    $queries = array_merge($queries, $tabs[$curTab]['query']);

    $defaultQueries = array();
    $user = Auth::getUser();
    if ($user instanceof User) {
        $defaultQueries['user_id'] = $user->user_id;
    }

    $billingStagingModel = new BillingStaging();
    $billingStagings = $billingStagingModel->search(array_merge($queries, $defaultQueries));

    return View::make('layout')->nest('content', 'user.billing_buyer',
        array('tabs' => $tabs, 'curTab' => $curTab, 'queries' => $queries, 'billingStagings' => $billingStagings));
}));

Route::get('/billing_mgr/{curTab?}', array('before' => 'auth', function ($curTab) {
    $tabs = array(
        'account_balance' => array(
            'label' => '账户收支明细',
            'url' => '/billing_mgr/account_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_ACCOUNT,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
        'points_balance' => array(
            'label' => '积分明细',
            'url' => '/billing_mgr/points_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_POINTS,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
    );

    $queries = Input::all();

    $queries = array_merge($queries, $tabs[$curTab]['query']);

    $billingStagingModel = new BillingStaging();
    $billingStagings = $billingStagingModel->search($queries, 20);

    return View::make('layout')->nest('content', 'user.billing_mgr',
        array('tabs' => $tabs, 'curTab' => $curTab, 'queries' => $queries, 'billingStagings' => $billingStagings));
}));

Route::get('/fsm_buy/{id?}', array('before' => 'auth', function ($id) {
    $instantOrder = InstantOrder::findOrFail($id);
    $fsm = new InstantOrderFsm($instantOrder);
    $fsm->apply('buy');
    try {
        $fsm->apply('pay_success');
    } catch (Exception $e) {
        $user = Auth::getUser();
        if ($instantOrder instanceof InstantOrder) {
            $iAmount = $instantOrder->quote_price;

            //添加一条充值记录
            $aRecharge = new Recharge();
            $aRecharge->user_id = $user['user_id'];
            $aRecharge->money = $iAmount;
            $aRecharge->type = 1; //支付方式
            $aRecharge->stat = 1; //初始化
            $aRecharge->createtime = time();
            $aRecharge->callback_action_id = $id;
            $aRecharge->callback_action_type = 1; //购买即时订单
            $aRecharge->save();
            $iRechargeID = $aRecharge->id;
        }
//        $iRechargeID = DB::table('gt_recharge')->insertGetId(array('user_id'=>$user['user_id'],'money'=>$iAmount,
//                'type'=>1,'stat'=>1,'createtime'=>time())

        //执行支付宝支付
        if (!empty ($iRechargeID) && !empty ($iAmount) && is_numeric($iAmount)) {
            $sHtmlText = Alipay::Payment($iAmount, sprintf("%08d", $iRechargeID), null, null, "付款", "付款");
            return $sHtmlText;
        }
    }
    return Redirect::to('instant_order_buyer');
}));

Route::get('/alipay_notify', array('before' => 'auth', function () {
    $aParams = $aError = array();
    $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
    $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
    $sPayNo = Input::get('trade_no'); //支付宝交易号
    $sTradeStatus = Input::get('trade_status'); //交易状态
    $sBuyer = Input::get('buyer_email');
    $notify = new Alipay;
    $bBes = $notify->notifyVerify(0x1003, intval($sTradeNo), $iMoney, $sPayNo, $sBuyer);
    if ($bBes) {
        return Redirect::to('instant_order_buyer');
    } else {
        echo 'fail';
    }
}));

Route::get('/alipay_return', array('before' => 'auth', function () {
    $aParams = $aError = array();
    $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
    $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
    $sPayNo = Input::get('trade_no'); //支付宝交易号
    $sTradeStatus = Input::get('trade_status'); //交易状态
    $sBuyer = Input::get('buyer_email');
    $notify = new Alipay;
    $bBes = $notify->returnVerify(0x1003, intval($sTradeNo), $iMoney, $sPayNo, $sBuyer);
    if ($bBes) {
        $rechargeId = intval($sTradeNo);
        $aRecharge = Recharge::where('id', '=', $rechargeId)->get();
        $instantOrderID = $aRecharge[0]->callback_action_id;
        $instantOrder = InstantOrder::findOrFail($instantOrderID);
        $fsm = new InstantOrderFsm($instantOrder);
        $fsm->apply('pay_success');
        return Redirect::to('instant_order_buyer');
    } else {
        echo 'fail';
    }
}));
