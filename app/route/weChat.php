<?php
Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT'], 'before' => 'weChatAuth'), function(){
    View::creator('mobile_layout', function (\Illuminate\View\View $view) {
        $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
    });

    View::creator('mobile_layout_hall', function (\Illuminate\View\View $view) {
        $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
    });

    Route::get('/', function(){
        return 'hello world!';
    });

    Route::get('/mobile_home/instant', function () {
        MobileLayout::$activeService = 'instant';

        $queries = Input::all();

        $curDate = date('Y-m-d');
        $queries['event_date_start'] = $curDate;

        $queries['state'] = array('on_sale');

        $instantOrder = new InstantOrder();
        $hallPriceAggregates = $instantOrder->searchHallPriceAggregate($queries, 20);

        $hallIds = array();
        foreach ($hallPriceAggregates as $hallPriceAggregate) {
            $hallIds[$hallPriceAggregate->hall_id] = $hallPriceAggregate->hall_id;
        }

        $halls = array();
        if (count($hallIds) > 0) {
            $hallDbResults = Hall::with('HallImages', 'Envelope')->whereIn('id', $hallIds)->get();
            foreach ($hallDbResults as $hallDbResult) {
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
        for ($i = 8; $i < 23; $i++) {
            $hours[$i] = sprintf('%s时 - %s时', $i, $i + 1);
        }
        return View::make('mobile_layout_hall')->nest('content', 'mobile.instant_hall',
            array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
                'halls' => $halls, 'dates' => $dates, 'hours' => $hours));

    });

    Route::get('/mobile_home/reserve/{curType?}', function ($curType) {
        MobileLayout::$activeService = 'reserve';

        $types = array(
            'recommend' => array(
                'label' => '推荐场馆',
                'url' => '/mobile_home/reserve/recommend',
            ),
            'nearby' => array(
                'label' => '附近场馆',
                'url' => '/mobile_home/reserve/nearby',
            ),
            'ordered' => array(
                'label' => '常订场馆',
                'url' => '/mobile_home/reserve/ordered',
            ),
        );
        if ($curType == 'recommend') {
            $Halls = HallActive::where('type', '=', 1)->get();

        } elseif ($curType == 'nearby') {
            $appUserID = Input::get('app_user_id');
            $time = strtotime(date('Y-m-d', time()));
            $location = WXLocation::where('openid', '=', $appUserID)->where('creattime', '<', $time)->orderBy('creattime', 'desc')->first();
            if ($location) {
                $lat = $location -> lat;
                $lon = $location -> lon;
                $Halls = DB::select('select `hall_id`,`long`,`lat`,ACOS(SIN((' . $lat . ' * 3.1415) / 180 ) * SIN((`lat` * 3.1415) / 180 ) + COS((' . $lat . '* 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS((' . $lon . ' * 3.1415) / 180 - (`long` * 3.1415) / 180 ) ) * 6380 as description from `gt_hall_tiny` as a join `gt_hall_map` as b on a.id=b.`hall_id` where
                          a.`stat` =2 and
                          b.`lat` > ' . $lat . '-1 and
                          b.`lat` < ' . $lat . '+1 and
                          b.`long` > ' . $lon . '-1 and
                          b.`long` <  ' . $lon . '+1 order by description asc limit 7');
            }
            else{$Halls =array();}
        } elseif ($curType == 'ordered') {
            $appUserID = Input::get('app_user_id');
            $app = RelationUserApp::where('app_user_id', '=', $appUserID)->first();
            if ($app) {
                $userID = $app->user_id;
                $Halls = ReserveOrder::where('user_id', '=', $userID)->orderBy('event_date', 'desc')->select( 'hall_id')->distinct()->get();


            }
            else{
                return Redirect::to(url_wrapper('/mobile_bond'));
            }
        }
        $hallIds = array();
        foreach ($Halls as $Hall) {
            $hallIds[$Hall->hall_id] = $Hall->hall_id;
        }

        $halls = array();
        if (count($hallIds) > 0) {
            $hallDbResults = Hall::with('HallPrices')->whereIn('id', $hallIds)->get();
            foreach ($hallDbResults as $hallDbResult) {
                $halls[$hallDbResult->id] = $hallDbResult;
            }
        }



        return View::make('mobile_layout_hall')->nest('content', 'mobile.reserve_hall',
            array('curType' => $curType, 'types' => $types,'Halls'=>$Halls,'halls' => $halls
            ));
    });

    Route::get('/mobile_buyer', function () {
        MobileLayout::$activeService = 'center';

        $user = Auth::getUser();
        $userID = $user['user_id'];

        $instantModel = new InstantOrder();

        $instant = $instantModel->select(array('state',DB::raw('COUNT(1) AS count')))->where('buyer','=',$userID)->groupBy('state')->get();
        foreach($instant as $ins){
            if($ins->state == 'paying'){
                $insPaying = $ins->count;
            }
            elseif($ins->state == 'payed'){
                $payed = $ins->count;
            }
        }

        $reserve = ReserveOrder::where('user_id','=',$user->user_id)->select(array('stat',DB::raw('COUNT(1) AS count')))->groupBy('stat')->get();
        foreach($reserve as $res){
            if($res->stat == '0'){
                $pending = $res->count;
            }
            elseif($res->stat == '1'){
                $resPaying = $res->count;
            }
        }
        if(empty($pending)){$pending=0;}
        if(empty($resPaying)){$resPaying=0;}
        if(empty($insPaying)){$insPaying=0;}
        if(empty($payed)){$payed=0;}

        return View::make('mobile_layout_hall')->nest('content', 'mobile.mobile_buyer',
            array('user' => $user, 'insPaying' => $insPaying, 'payed' => $payed ,'resPaying'=>$resPaying,'pending'=>$pending));

    });


    Route::get('/mobile_bond', function () {
        $queries = Input::all();
        $app = RelationUserApp::where('app_user_id','=',$queries['app_user_id'])->first();
        if($app){
            return Redirect::to(url_wrapper('/mobile_buyer'));
        }else{
            MobileLayout::$activeService = 'center';
            MobileLayout::$title = '绑定';

            if (isset($queries['nickname']) && isset($queries['password'])) {

                $nickname = $queries['nickname'];
                $password = $queries['password'];
                $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
                $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
                if ($isNickLog | $isTeleLog) {
                    if (Auth::check()) {
                        $user = Auth::getUser();
                        $userID = $user->user_id;
                        $app = RelationUserApp::where('user_id', '=', $userID)->first();
                        if (!$app) {
                            $app = new RelationUserApp;
                            $app->user_id = $userID;
                            $app->app_id = $queries['app_id'];
                            $app->app_user_id = $queries['app_user_id'];
                            $app->save();
                        } else {
                            if ($app instanceof RelationUserApp) {
                                $app->app_user_id = $queries['app_user_id'];
                                $app->save();
                            }
                        }
                    }
                    return View::make('mobile_layout')->nest('content', 'mobile.bond_success', array('user' => $user));
                }
            } else {

                return View::make('mobile_layout')->nest('content', 'mobile.bond', array('queries' => $queries));
            }
        }
    });

    Route::get('/mobile_register', function () {
        $queries = Input::all();
        $app = RelationUserApp::where('app_user_id','=',$queries['app_user_id'])->first();
        if($app){
            return Redirect::to(url_wrapper('/mobile_buyer'));
        }else{
            MobileLayout::$activeService = 'center';
            return View::make('mobile_layout')->nest('content', 'mobile.register', array('queries' => $queries));
        }
    });

    Route::post('/mobile_register', function(){

        $rules = array(
            'nickname'              => 'required|unique:gt_user_tiny,nickname',
            'realname'              => 'required',
            'password'              => 'required|between:6,20|confirmed',
            'password_confirmation' => 'required|between:6,20',
            'telephone'             => 'required|digits:11|unique:gt_user_tiny,telephone',
            'validcode'             => 'required|digits:4',
        );
        $messages = array(
            'required'        => '请确保每项都填入了您的信息',
            'nickname.unique' => '昵称已经被注册，换个昵称试试',
            'confirmed'       => '两次输入的密码不相同',
            'between'         => '密码需要在6-20位之间',
            'telephone.digits'=> '请输入有效的电话号码',
            'telephone.unique'=> '该电话号码已经注册过网球通帐号',
            'validcode.digits'=> '请输入正确的验证码',
        );

        $validator = Validator::make(Input::all(), $rules,$messages);

        if ($validator->fails())
        {

            MobileLayout::$title = '注册';
            return Redirect::to(url_wrapper('/mobile_register'))->withErrors($validator);
        }else{

            MobileLayout::$title = '注册成功';
            $user = new User;
            $user->nickname = Input::get('nickname');
            $user->realname = Input::get('realname');
            $user->telephone = Input::get('telephone');
            $user->password = md5(Input::get('password'));

            $user->save();
            $app = RelationUserApp::where('app_user_id', '=', Input::get('app_user_id'))->first();
            if (!$app) {
                $app = new RelationUserApp;
                $app->user_id = $user->user_id;
                $app->app_id = Input::get('app_id');
                $app->app_user_id = Input::get('app_user_id');
                $app->save();
            } else {
                if ($app instanceof RelationUserApp) {
                    $app->app_user_id = $user->user_id;;
                    $app->save();
                }
            }


            return View::make('mobile_layout')->nest('content', 'mobile.reg_success',array('user'=>$user));

        }


    });



    Route::get('/mobile_buyer_order', function () {
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = "我的即时订单";
        MobileLayout::$previousUrl = '/mobile_buyer';

        $queries = Input::all();

        $user = Auth::getUser();
        $userID = $user['user_id'];
        $instantModel = new InstantOrder();
        $queries['buyer'] = $userID;
        $label = Input::get('state');
        if(!$label){
            $label = 'all';
        }


        $instants = $instantModel->search($queries);


        return View::make('mobile_layout')->nest('content', 'mobile.order_buyer',
            array('user' => $user, 'instants' => $instants,'label'=>$label));

    });

    Route::get('/hall_reserve', function(){
        MobileLayout::$activeService = 'reserve';
        MobileLayout::$title = '填写订单';
        MobileLayout::$previousUrl = URL::previous();

        $hallID = Input::get('hall_id');
        $hall = Hall::find($hallID);
        $user = Auth::getUser();

        $weekdayOption = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
        $dates = array();
        for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = sprintf('%s（%s）', date('m月d日', $time), $weekdayOption[date('w', $time)]);
        }
        $hours = array('不限');
        for($i=7; $i<=24; $i++){
            $hours[$i] = sprintf('%s时', $i, $i +1);
        }

        return View::make('mobile_layout')->nest('content', 'mobile.hall_reserve',
            array('hall'=>$hall,'user'=>$user,'dates'=>$dates, 'hours' => $hours));
    });

    Route::get('/mobile_court_buyer/{hallID?}', function($hallID){
        MobileLayout::$activeService = 'instant';
        MobileLayout::$previousUrl = URL::previous();

        $hall = Hall::findOrFail($hallID);

        $activeDate = Input::get('date');
        $activeDateTimeStamp =  empty($activeDate) ? time() : strtotime($activeDate);
        $activeDate = date('Y-m-d', $activeDateTimeStamp);

        $dates = array();
        for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = $time;
        }

        $instantOrders = InstantOrder::orderBy('start_hour', 'asc')
            ->where('hall_id', '=', $hallID)->where('event_date', '=', $activeDate)->get();

        $formattedInstants = array();
        foreach ($instantOrders as $instant) {
            !isset($formattedInstants[$instant->court_id]) && $formattedInstants[$instant->court_id] = array();
            $formattedInstants[$instant->court_id][$instant->start_hour] = $instant;
            $instant['select'] = false;
        }

        $courts = Court::where('hall_id', '=', $hallID)->get();

        MobileLayout::$title = $hall->name;
        return View::make('mobile_layout')->nest('content', 'mobile.court_buyer',array(
            'halls' => array($hall), 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
            'activeDate' => $activeDate, 'courts' => $courts,  'formattedInstants' => $formattedInstants,
            'loginUserId' => Auth::getUser()->user_id, 'instantOrders'=>$instantOrders, 'noMoney'=>array(
                'needPay'=>0, 'balance'=>0, 'needRecharge'=>0,'adviseForwardUrl'=>''
            )
        ));
    });

    Route::post('/submit_reserve_order',function(){
        $queries = Input::all();
        $order = new ReserveOrder;
        $order->hall_id = $queries['hall_id'];
        $order->user_id = $queries['user_id'];
        $order->start_time = $queries['start_time'];
        $order->end_time = $queries['end_time'];
        $order->createtime = time();
        $order->cost = $queries['price'];
        $order->court_num = $queries['court_num'];
        $order->event_date = strtotime($queries['event_date']);
        $order->createuser = $queries['user_id'];
        $order->save();

        Notify::doNotify('mgr_reserve_order_created', $order->id);

        return Redirect::to(url_wrapper('/reserve_order_buyer'));
    });

    Route::get('/reserve_order_buyer', function(){
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '我的预约订单';
        MobileLayout::$previousUrl = '/mobile_buyer';


        //展示预定订单
        $user = Auth::getUser();
        $stat = Input::get('stat');
        if(isset($stat)){
            $orderDbResults = ReserveOrder::with('Hall')->where('user_id','=',$user->user_id)->where('stat','=',$stat)->orderBy('event_date','desc')->get();
        }
        else{
            $orderDbResults = ReserveOrder::with('Hall')->where('user_id','=',$user->user_id)->orderBy('event_date','desc')->get();
            $stat = '7';
        }

        $reserves = array();
        foreach ($orderDbResults as $orderDbResult) {
            $reserves[$orderDbResult->id] = $orderDbResult;
        }

        return View::make('mobile_layout')->nest('content', 'mobile.reserve_order_buyer',
            array('reserves'=>$reserves,'stat'=>$stat));
    });

    Route::get('/pay_success', function(){
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '支付成功';
        MobileLayout::$previousUrl = url_wrapper('/mobile_buyer');



        return View::make('mobile_layout')->nest('content','mobile.pay_success');

    });

    Route::post('/telValidCodeMake',function(){
        $telephone = Input::get('telephone');
        $iCode = rand(1000,9999);
        if (Cache::has($telephone))
        {
            Cache::forget($telephone);
        }
        Cache::put($telephone,$iCode, 3600*6);
        Sms::sendASync($telephone, '您的手机验证码为'.$iCode.'感谢您对网球通的支持。以后打球不办卡，办卡就找【网球通】。', '');
        echo 'true';
    });

    Route::post('/telValidCodeValid',function(){
        $telephone = Input::get('telephone');
        $validcode = Input::get('validcode');

        if (Cache::has($telephone) && (Cache::get($telephone) == $validcode))
        {
            echo 'true';
        }else{
            echo 'false';
        }
    });

    Route::post('/nicknameValid',function(){
        $nickname = Input::get('nickname');
        $user = User::where('nickname','=',$nickname)->first();
        if($user){
            echo 'false';
        }
        else{
            echo 'true';
        }
    });

    Route::post('/telephoneValid',function(){
        $telephone = Input::get('telephone');
        $user = User::where('telephone','=',$telephone)->first();
        if($user){
            echo 'false';
        }
        else{
            echo 'true';
        }
    });

    Route::post('/bondValid',function(){
        $nickname = Input::get('nickname');
        $password = Input::get('password');

        $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
        $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
        if ($isNickLog || $isTeleLog) {
            echo 'true';
        }
        else{
            echo 'false';
        }
    });
});



