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
        $worktableService = new InstantOrderWorktable();
        return rest_success($worktableService->loadWorktableByHallAndDate($hall, $date));
    });

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
        for ($i = 0; $i < 7; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = $time;
        }

        $worktableService = new InstantOrderWorktable();
        $workTableData = $worktableService->loadWorktableByHallAndDate($hallID, $activeDate);

        return View::make('layout')->nest('content', 'instantOrder.order_court_manage_knock_out', array(
            'halls' => $halls, 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
            'activeDate' => $activeDate, 'worktableData' => $workTableData
        ));

    }));

    Route::post('/hall/instantOrder/batchOperate/{operate?}', function($operate){
        $instantOrderIdString = Input::get('instant_order_ids');
        $instantOrderIds = explode(',', $instantOrderIdString);

        $res = array('failed' => array(), 'total' => count($instantOrderIds), 'success' => 0, 'original' => $instantOrderIdString);

        $fsm = new InstantOrderFsm();
        foreach($instantOrderIds as $instantOrderId){
            try{
                $instantOrder = InstantOrder::findOrFail($instantOrderId);
                $fsm->resetObject($instantOrder);
                $fsm->apply($operate);

                $res['success'] ++ ;
            }catch (\Exception $e){
                $res['failed'][$instantOrderId] = $e->getTraceAsString();
            }
        }

        return rest_success($res);
    });

    Route::get('controller', 'WeiXinController@test');
});