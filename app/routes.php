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

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::getUser();
        $roles = $user->roles;
        $role = $roles[0]->role_id;
        if ($role == 1) {
            return Redirect::to('hall_on_sale');
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

Route::get('/hall_on_sale', array('before' => 'auth', function () {
    $queries = Input::all();

    $curDate = date('Y-m-d');
    $queries['event_date_start'] = $curDate;

    $queries['state'] = array('on_sale');

    $instantOrder = new InstantOrder();
    $hallPriceAggregates = $instantOrder->searchHallPriceAggregate($queries, 8);
    $hallIds = array();
    foreach($hallPriceAggregates as $hallPriceAggregate){
        $hallIds[$hallPriceAggregate->hall_id] = $hallPriceAggregate->hall_id;
    }

    $halls = array();
    if(count($hallIds) > 0){
        $hallDbResults = Hall::with('HallImages', 'Envelope')->whereIn('id', $hallIds)->get();
        foreach($hallDbResults as $hallDbResult){
            $halls[$hallDbResult->id] = $hallDbResult;
        }
    }

    $weekdayOption = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
    $dates = array('不限');
    for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
        $time = strtotime("+$i day");
        $dates[date('Y-m-d', $time)] = sprintf('%s（%s）', date('m月d日', $time), $weekdayOption[date('w', $time)]);
    }

    $hours = array('不限');
    for($i=8; $i<23; $i++){
        $hours[$i] = sprintf('%s时 - %s时', $i, $i +1);
    }

    return View::make('layout')->nest('content', 'instantOrder.hall_on_sale',
        array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
            'halls' =>$halls, 'dates' => $dates, 'hours' => $hours));
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
    $halls = $user->Halls;
    if (!$hallID && count($halls) > 0) {
        $hallID = $halls[0]->id;
    }

    $activeDate = Input::get('date');
    empty($activeDate) && $activeDate = date('Y-m-d');

    $dates = array();
    for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
        $time = strtotime("+$i day");
        $dates[date('Y-m-d', $time)] = $time;
    }

    $worktableService = new InstantOrderManager();
    $workTableData = $worktableService->loadWorktableByHallAndDate($hallID, $activeDate);

    return View::make('layout')->nest('content', 'instantOrder.order_court_manage', array(
        'halls' => $halls, 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
        'activeDate' => $activeDate, 'worktableData' => $workTableData
    ));
}));

Route::post('/hall/instantOrder/batchOperate', array('before' => 'auth',function(){
    $operate = Input::get('operate');
    $instantOrderIdString = Input::get('instant_order_ids');
    $instantOrderIds = explode(',', $instantOrderIdString);

    $res = array('failed' => array(), 'total' => count($instantOrderIds), 'success' => 0, 'original' => $instantOrderIdString);

    $instants = InstantOrder::whereIn('id', $instantOrderIds)->get();
    $fsm = new InstantOrderFsm();
    foreach($instants as $instant){
        try{
            $fsm->resetObject($instant);
            $fsm->apply($operate);

            $res['success'] ++ ;
        }catch (\Exception $e){
            $res['failed'][$instant->id] = $e->getTraceAsString();
        }
    }

    return rest_success($res);
}));

Route::get('/order_court_buyer/{hallID?}', array('before' => 'weixin auth', function ($hallID) {
    $hall = Hall::findOrFail($hallID);

    $activeDate = Input::get('date');
    empty($activeDate) && $activeDate = date('Y-m-d');

    $dates = array();
    for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
        $time = strtotime("+$i day");
        $dates[date('Y-m-d', $time)] = $time;
    }

    $worktableService = new InstantOrderManager();
    $workTableData = $worktableService->loadWorktableByHallAndDate($hallID, $activeDate);

    return View::make('layout')->nest('content', 'instantOrder.order_court_buyer', array(
        'halls' => array($hall), 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
        'activeDate' => $activeDate, 'worktableData' => $workTableData
    ));

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

        //执行支付宝支付
        if (!empty ($iRechargeID) && !empty ($iAmount) && is_numeric($iAmount)) {
            $sHtmlText = Alipay::Payment($iAmount, sprintf("%08d", $iRechargeID), null, null, "付款", "付款");
            return $sHtmlText;
        }
    }
    return Redirect::to('instant_order_buyer');
}));

Route::post('/instantOrder/batchBuy', array('before' => 'auth',function(){
    $instantOrderIdString = Input::get('instant_order_ids');
    $instantOrderIds = explode(',', $instantOrderIdString);

    $result = array();

    DB::beginTransaction();
    try{
        $instants = InstantOrder::whereIn('id', $instantOrderIds)->get();

        if(count($instants) != count($instantOrderIds)){
            throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($instantOrderIds), count($instants)));
        }

        $fsm = new InstantOrderFsm();

        //跳转到--正在支付中
        foreach($instants as $instant){
            $fsm->resetObject($instant);
            $fsm->apply('buy');
        }

        //获取总共需要支付的钱数
        $needPay = 0;
        foreach($instants as $instant){
            $needPay += $instant->quote_price;
        }

        //获取用户当前余额
        $user = Auth::getUser();
        $account = Finance::getUserAccount($user->user_id, \Sports\Constant\Finance::PURPOSE_ACCOUNT);

        //如果可用余额不够 进支付宝，否则轮询支付
        if($account->getAvailableAmount() < $needPay){
            $result['advise_forward_url'] = sprintf('/recharge/alipay/%s/%s/%s',
                $needPay, RECHARGE_CALLBACK_PAY_INSTANT_ORDER, implode(',', $instantOrderIds));

            $result['status'] = 'no_money';
        }else{
            foreach($instants as $instant){
                $fsm->resetObject($instant);
                $fsm->apply('pay_success');
            }

            $result['advise_forward_url'] = '/instant_order_buyer';

            $result['status'] = 'pay_success';
            throw new Exception(11);
        }
        DB::commit();
    }catch (Exception $e){
        DB::rollBack();
        throw $e;
    };

    return rest_success($result);
}));
