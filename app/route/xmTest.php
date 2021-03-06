<?php

Route::group(array('prefix' => 'xm'), function () {

    Route::get('/', function () {
        $user = User::take(10)->offset(10)->where('privilege', '=', 2)->where('prestore_fee', '>', 200)->get();
        return count($user);
    });


    Route::get('/page', function () {
        $users = User::where(function (\Illuminate\Database\Eloquent\Builder $query) {
            if (Input::get('nickname')) {
                $query->where('nickname', 'like', '%' . Input::get('nickname') . '%');
            }
            if (Input::get('telephone')) {
                $query->where('telephone', 'like', Input::get('telephone'));
            }
        })
            ->paginate(2);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users));
    });

    Route::get('/search', function () {
        $queries = Input::all();

        $userModel = new User();
        $users = $userModel->search($queries);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users, 'queries' => $queries));
    });

    Route::get('/sport', function () {
        return \Sports\Constant\Finance::ACCOUNT_BALANCE;
    });

    Route::get('/user-role', function () {
        $roles = User::find(888928)->roles;
        return View::make('xm.user.user-role', array('roles' => $roles));
    });

    Route::get('/role-header', function () {
        $headers = Role::find(1)->headers;
        return View::make('xm.user.role-header', array('headers' => $headers));
    });

    Route::get('/header-header', function () {
        $headers = Header::find(0)->children;
        return View::make('xm.user.header-header', array('headers' => $headers));
    });

    Route::get('/acl-cfg', function () {
        $roles = user::find(888928)->roles;

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
        return $view = View::make('home')->nest('top', 'format.top')->nest('header', 'format.header', array('headers' => $headers, 'acl' => $acl));
    });

    Route::get('/fsm-init', function () {
        $instantOrder = InstantOrder::create(array());
        $fsm = new InstantOrderFsm($instantOrder);
        echo $fsm->getCurrentState();
    });

    Route::get('/fsm-operate/{id?}/{operate?}', function ($id, $operate) {
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $previousState = $fsm->getCurrentState();

        $fsm->apply($operate);

        echo "<br/>";
        echo $previousState;
        echo '->';
        echo $fsm->getCurrentState();
    });

    Route::get('/artisan', function () {
//        Artisan::call('instantOrder:generate', array('--date' => '2014-10-01', '--hall' =>8920));
        $out = new \Symfony\Component\Console\Output\BufferedOutput();
        Artisan::call('user:hall', array('operate' => 'generate', '--hall' => array(8920)), $out);
        return $out->fetch();
    });

    Route::get('/register', function () {
//        User::create(array('nickname' => 'hall8888', 'password' =>Hash::make('123456')));
        $res = Auth::validate(array('nickname' => 'hall8888', 'password' => '123456'));
        echo $res;
    });

    Route::get('/finance/freeze/{id?}', function ($iInstantOrderId) {
        $instantOrder = InstantOrder::findOrFail($iInstantOrderId);

        $instantOrderFinance = new InstantOrderFinance($instantOrder);
        $instantOrderFinance->buy();

        $buyerAccount = Finance::getUserAccount($instantOrder->buyer, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $sellerAccount = Finance::getUserAccount($instantOrder->seller, \Sports\Constant\Finance::PURPOSE_ACCOUNT);

        return array('buyer' => $buyerAccount->toArraySerializable(), 'seller' => $sellerAccount->toArraySerializable());
    });

    Route::get('finance/execute/{id?}', function ($iInstantOrderId) {
        $instantOrder = InstantOrder::findOrFail($iInstantOrderId);

        $instantOrderFinance = new InstantOrderFinance($instantOrder);
        $instantOrderFinance->execute();

        $buyerAccount = Finance::getUserAccount($instantOrder->buyer, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $sellerAccount = Finance::getUserAccount($instantOrder->seller, \Sports\Constant\Finance::PURPOSE_ACCOUNT);

        return array('buyer' => $buyerAccount->toArraySerializable(), 'seller' => $sellerAccount->toArraySerializable());
    });


    Route::get('instantOrder/view/{hall?}/{date?}', function ($hall, $date) {
        $worktableService = new InstantOrderManager();
        return rest_success($worktableService->loadWorktableByHallAndDate($hall, $date));
    });

    Route::get('mysql-connection', function () {
        $dbConfig = \Config::get('database.connections.mysql');
        $dbConfig['driver'] = 'Mysqli';
        $dbConfig['options'] = array('buffer_results' => true);

//        $finance = new \Sports\Finance\FinanceService(new \Zend\Db\Adapter\Adapter($dbConfig));
//        $account = $finance->getUserAccount(889082, 1);

        $pdoFinance = new \Sports\Finance\FinanceService(new \Zend\Db\Adapter\Adapter(new \Zend\Db\Adapter\Driver\Pdo\Pdo(DB::connection()->getPdo())));
        $pdoAccount = $pdoFinance->getUserAccount(889082, 1);

        $mysqlConnection = DB::connection()->getPdo();
        if ($mysqlConnection instanceof \Doctrine\DBAL\Driver\Mysqli\MysqliConnection) {
            $resourceHandle = $mysqlConnection->getWrappedResourceHandle();
            $i = 1;
        }
        $mysqlConnection->lastInsertId();
    });

    Route::get('cache', function () {
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
        Cache::put('test-key', 'test-value', $expiresAt);

        return Cache::get('test-key');
    });

    Route::get('notify', function () {
//        return Notify::getRecord(NOTIFY_TYPE_ORDER_NOTICE, 16891, NOTIFY_CHANNEL_SMS_SYNC);

        $result = array();
        $result[] = Notify::sendWithBusiness(NOTIFY_TYPE_ORDER_NOTICE, 16891);

        $result[] = Notify::sendWithBusiness(NOTIFY_TYPE_USER_INSTANT_ORDER_PAYED, 12254);

        $result[] = Notify::sendWithBusiness(NOTIFY_TYPE_HALL_INSTANT_ORDER_SOLD, 12254);

        return $result;
    });

    Route::get('log', function () {
        return View::make('mobile.wechat_pay', array('jsApiParameters' => '{}'));
    });

    Route::get('test', function () {
        $userFinanceService = new UserFinance();
        $userFinanceService->doPaySuccess(1411621305, 'test', 360);
    });

    Route::get('wx_notify', function () {
        //return Notify::notifyUserWithCustomMsg(890490, 'test', array(NOTIFY_CHANNEL_WX_SYNC));

        return Notify::sendByChannel(890569, 'test', array(NOTIFY_CHANNEL_WX_SYNC));
    });

    Route::get('env', function () {
        return App::environment();
    });

    Route::get('transfer', function () {
        $userFinance = new UserFinance();
        $userFinance->transfer(890490, 889082, null, '微信更换绑定的网球通账户');
    });

    Route::get('wxProduct', function () {
        $client = new \Cooper\Wechat\WeChatClient();
        $res = $client->getOnlineProduct();
        return $res;
    });

    Route::get('curl', function () {
        $client = new \Cooper\Wechat\WeChatClient();
        return $client->getMenu();
    });

    Route::get('qr', function () {
        $client = new \Cooper\Wechat\WeChatClient();
        $ticket = $client->getQrcodeTicket(array('scene_id' => 100000));
        $image = $client->getQrcodeImgUrlByTicket($ticket);
        return array('ticket' => $ticket, 'image' => $image);
    });

    Route::get('access_token', function () {
        $client = new \Cooper\Wechat\WeChatClient();
        $result = $client->getAccessToken();
        return $result;
    });

    Route::get('seeking/expire', function () {
        Artisan::call('seeking:expire');
    });

    Route::get('shortlink', function () {
        #字符表
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        $key = "alexis";
        $urlhash = md5($key . 'ozSPvjswNu3dXez66rMCD6pccpSw');
        $len = strlen($urlhash);
        $short_url_list = array();
        #将加密后的串分成4段，每段4字节，对每段进行计算，一共可以生成四组短连接
        for ($i = 0; $i < 4; $i++) {
            $urlhash_piece = substr($urlhash, $i * $len / 4, $len / 4);
            #将分段的位与0x3fffffff做位与，0x3fffffff表示二进制数的30个1，即30位以后的加密串都归零
            $hex = hexdec($urlhash_piece) & 0x3fffffff; #此处需要用到hexdec()将16进制字符串转为10进制数值型，否则运算会不正常

            $short_url = "";
            #生成6位短连接
            for ($j = 0; $j < 6; $j++) {
                #将得到的值与0x0000003d,3d为61，即charset的坐标最大值
                $short_url .= $charset[$hex & 0x0000003d];
                #循环完以后将hex右移5位
                $hex = $hex >> 5;
            }

            $short_url_list[] = $short_url;
        }

        return $short_url_list;
    });
});

Route::group(array('domain' => 'homestead1.app'), function () {

    Route::get('/', function () {
        return View::make('mobile.wechat_pay', array('jsApiParameters' => '{}'));
    });

});

Route::group(array('domain' => 'homestead2.app'), function () {

    Route::get('/', function () {
        return "I am homestead2";
    });

});