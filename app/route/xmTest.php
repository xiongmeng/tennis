<?php

Route::group(array('prefix' => 'xm'), function(){

    Route::get('/', function(){
        $user = User::take(10)->offset(10)->where('privilege', '=', 2)->where('prestore_fee', '>', 200)->get();
        return count($user);
    });


    Route::get('/page', function(){
        $users = User::where(function(\Illuminate\Database\Eloquent\Builder $query){
            if(Input::get('nickname')){
                $query->where('nickname', 'like', '%' . Input::get('nickname') . '%');
            }
            if(Input::get('telephone')){
                $query->where('telephone', 'like', Input::get('telephone'));
            }
        })
       ->paginate(2);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users));
    });

    Route::get('/search', function(){
        $queries = Input::all();

        $userModel = new User();
        $users = $userModel->search($queries);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users, 'queries' => $queries));
    });

    Route::get('/sport', function(){
        return \Sports\Constant\Finance::ACCOUNT_BALANCE;
    });

    Route::get('/user-role', function(){
        $roles = User::find(888928)->roles;
        return View::make('xm.user.user-role', array('roles' => $roles));
    });

    Route::get('/role-header', function(){
        $headers = Role::find(1)->headers;
        return View::make('xm.user.role-header', array('headers' => $headers));
    });

    Route::get('/header-header', function(){
        $headers = Header::find(0)->children;
        return View::make('xm.user.header-header', array('headers' => $headers));
    });

    Route::get('/acl-cfg', function(){
        $roles = user::find(888928)->roles;

        $roleIds = array();
        foreach($roles as $role){
            $roleIds[] = $role->role_id;
        }

        $headers = Config::get('acl.headers');

        $allRolesHeaders = Config::get('acl.roles_headers');

        $acl = array();
        foreach($allRolesHeaders as $roleId => $rolesHeaders){
            if(in_array($roleId, $roleIds)){
                $acl = array_merge($acl, $rolesHeaders);
            }
        }
        $data = array('headers' => $headers, 'acl' => $acl);
        return $view = View::make('home')->nest('top','format.top')->nest('header', 'format.header',array('headers' => $headers, 'acl' => $acl) );
    });

    Route::get('/fsm-init', function(){
        $instantOrder = InstantOrder::create(array());
        $fsm = new InstantOrderFsm($instantOrder);
        echo $fsm->getCurrentState();
    });

    Route::get('/fsm-operate/{id?}/{operate?}', function($id, $operate){
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $previousState = $fsm->getCurrentState();

        $fsm->apply($operate);

        echo "<br/>";
        echo $previousState;
        echo '->';
        echo $fsm->getCurrentState();
    });

    Route::get('/artisan', function(){
        Artisan::call('instantOrder:generate', array('--date' => array('2014-07-30')),
            new \Symfony\Component\Console\Output\StreamOutput(fopen(storage_path() . '/logs/artisan.log', 'w')));
    });

    Route::get('/register', function(){
//        User::create(array('nickname' => 'hall8888', 'password' =>Hash::make('123456')));
        $res = Auth::validate(array('nickname' => 'hall8888', 'password' => '123456'));
        echo $res;
    });

    Route::get('/finance/freeze/{id?}', function($iInstantOrderId){
        $instantOrder = InstantOrder::findOrFail($iInstantOrderId);

        $instantOrderFinance = new InstantOrderFinance($instantOrder);
        $instantOrderFinance->buy();

        $buyerAccount = Finance::getUserAccount($instantOrder->buyer, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $sellerAccount = Finance::getUserAccount($instantOrder->seller, \Sports\Constant\Finance::PURPOSE_ACCOUNT);

        return array('buyer' => $buyerAccount->toArraySerializable(), 'seller' =>$sellerAccount->toArraySerializable());
    });

    Route::get('finance/execute/{id?}', function($iInstantOrderId){
        $instantOrder = InstantOrder::findOrFail($iInstantOrderId);

        $instantOrderFinance = new InstantOrderFinance($instantOrder);
        $instantOrderFinance->execute();

        $buyerAccount = Finance::getUserAccount($instantOrder->buyer, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $sellerAccount = Finance::getUserAccount($instantOrder->seller, \Sports\Constant\Finance::PURPOSE_ACCOUNT);

        return array('buyer' => $buyerAccount->toArraySerializable(), 'seller' =>$sellerAccount->toArraySerializable());
    });


    Route::get('instantOrder/view/{hall?}/{date?}', function($hall, $date){
        $worktableService = new InstantOrderManager();
        return rest_success($worktableService->loadWorktableByHallAndDate($hall, $date));
    });

    Route::get('mysql-connection', function(){
        $dbConfig = \Config::get('database.connections.mysql');
        $dbConfig['driver'] = 'Mysqli';
        $dbConfig['options'] = array('buffer_results' => true);

//        $finance = new \Sports\Finance\FinanceService(new \Zend\Db\Adapter\Adapter($dbConfig));
//        $account = $finance->getUserAccount(889082, 1);

        $pdoFinance = new \Sports\Finance\FinanceService(new \Zend\Db\Adapter\Adapter(new \Zend\Db\Adapter\Driver\Pdo\Pdo(DB::connection()->getPdo())));
        $pdoAccount = $pdoFinance->getUserAccount(889082, 1);

        $mysqlConnection = DB::connection()->getPdo();
        if($mysqlConnection instanceof \Doctrine\DBAL\Driver\Mysqli\MysqliConnection){
            $resourceHandle = $mysqlConnection->getWrappedResourceHandle();
            $i = 1;
        }
        $mysqlConnection->lastInsertId();
    });

    Route::get('cache', function(){
//        $users = DB::table('gt_user_tiny')->remember(10)->first();

        User::where('nickname', '=', 'rener')->remember(1)->get();
        User::where('telephone', '=', '18611367408')->remember(1)->get();


//        $rener = User::where('nickname', '=', 'rener')->get();
//
//        $rener->

//        $instantOrder = InstantOrder::remember(10)->findOrFail(12253);
//        $instantOrder->generated_price=150;
//        $instantOrder->save();
//        return $instantOrder;

//        $instantOrders = InstantOrder::remember(10)->limit(2)->get();
//        foreach($instantOrders as $instantOrder){
//            $instantOrders->generated_price=150;
//            $instantOrders->save();
//        }
//        return $instantOrders;

        $expiresAt = \Carbon\Carbon::now()->addMinutes(10);
        Cache::put('test-key','test-value', $expiresAt);

        return Cache::get('test-key');
    });

    Route::get('notify', function(){
//        Notify::doNotify('mgr_reserve_order_created', 16891);
//
//        Notify::doNotify('user_instant_order_payed', 20857);

        Notify::doNotify('hall_instant_order_sold', 20857);
    });

    Route::get('log', function(){
        return View::make('mobile.wechat_pay', array('jsApiParameters' => '{}'));
    });

    Route::get('test', function(){
        $userFinanceService = new UserFinance();
        $userFinanceService->doPaySuccess(1411621305, 'test', 360);
    });
});

Route::group(array('domain' => 'homestead1.app'), function()
{

    Route::get('/', function()
    {
        return View::make('mobile.wechat_pay', array('jsApiParameters' => '{}'));
    });

});

Route::group(array('domain' => 'homestead2.app'), function()
{

    Route::get('/', function()
    {
        return "I am homestead2";
    });

});