<?php

View::creator('mobile_layout', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
});

//View::creator('format.mobile.footer', function ($view) {
//    if (Auth::check()) {
//        $user = Auth::getUser();
//        $userID = $user->user_id;
//        $instantModel = new InstantOrder();
//        $queries['buyer'] = $userID;
//        $queries['state'] = 'paying';
//        $payingInstants = $instantModel->search($queries);
//        $paying = $payingInstants->count();
//        $queries['state'] = 'payed';
//        $payedInstants = $instantModel->search($queries);
//        $payed = $payedInstants->count();
//        if ($payed == 0 && $paying == 0) {
//            $isActive = false;
//        } else {
//            $isActive = true;
//        }
//        $view->with('isActive', $isActive);
//    }
//});

Route::get('/mobile_home/instant', function () {
    MobileLayout::$activeService = 'instant';

    $queries = Input::all();

    $curDate = date('Y-m-d');
    $queries['event_date_start'] = $curDate;

    $queries['state'] = array('on_sale');

    $instantOrder = new InstantOrder();
    $hallPriceAggregates = $instantOrder->searchHallPriceAggregate($queries, 8);

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
    return View::make('mobile_layout')->nest('content', 'mobile.instant_hall',
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
            $Halls = DB::select('select `hall_id`,`long`,`lat`,ACOS(SIN((" . $lat . " * 3.1415) / 180 ) * SIN((`lat` * 3.1415) / 180 ) + COS((' . $lat . '* 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS((' . $lon . ' * 3.1415) / 180 - (`long` * 3.1415) / 180 ) ) * 6380 as description from `gt_hall_tiny` as a join `gt_hall_map` as b on a.id=b.`hall_id` where
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
            //跳转绑定
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



    return View::make('mobile_layout')->nest('content', 'mobile.reserve_hall',
        array('curType' => $curType, 'types' => $types,'Halls'=>$Halls,'halls' => $halls
        ));
});

Route::get('/mobile_buyer', array('before' => 'weixin', function () {
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

    $reserve = Order::where('user_id','=',$user->user_id)->select(array('stat',DB::raw('COUNT(1) AS count')))->groupBy('stat')->get();
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

    return View::make('mobile_layout')->nest('content', 'mobile.mobile_buyer',
        array('user' => $user, 'insPaying' => $insPaying, 'payed' => $payed ,'resPaying'=>$resPaying,'pending'=>$pending));

}));


Route::get('/mobile_bond', function () {
    MobileLayout::$activeService = 'center';

    $queries = Input::all();

    if (isset($queries['nickname']) && isset($queries['password'])) {
        $queries = Input::all();
        $nickname = $queries['nickname'];
        $password = $queries['password'];
        $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
        $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
        if ($isNickLog | $isTeleLog) {
            if (Auth::check()) {
                $user = Auth::getUser();
                $userID = Auth::user()->user_id;
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
            return View::make('layout')->nest('content', 'bond_success', array(''));
        } else {
            echo '绑定失败，用户名密码错误';
        }
    } else {
        return View::make('layout')->nest('content', 'bond', array('queries' => $queries));
    }
});

Route::get('/mobile_buyer_order', array('before' => 'weixin', function () {
    MobileLayout::$activeService = 'center';

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

}));

Route::get('/hall_reserve',array('before'=>'weixin',function(){
    MobileLayout::$activeService = 'reserve';

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
    for($i=7; $i<23; $i++){
        $hours[$i] = sprintf('%s时', $i, $i +1);
    }

    return View::make('mobile_layout')->nest('content', 'mobile.hall_reserve',
        array('hall'=>$hall,'user'=>$user,'dates'=>$dates, 'hours' => $hours));
}));

Route::get('/mobile_court_buyer/{hallID?}', array('before' => 'auth', function($hallID){
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
}));

Route::post('/submit_reserve_order',array('before'=>'weixin',function(){
    $queries = Input::all();
    $order = new Order;
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
    return Redirect::to(url_wrapper('/reserve_order_buyer'));
}));

Route::get('/reserve_order_buyer',array('before'=>'weixin',function(){
    MobileLayout::$activeService = 'center';



    //展示预定订单
    $user = Auth::getUser();
    $stat = Input::get('stat');
    if(isset($stat)){
        $reserveOrders = Order::where('user_id','=',$user->user_id)->where('stat','=',$stat)->orderBy('event_date','desc')->get();
    }
    else{
    $reserveOrders = Order::where('user_id','=',$user->user_id)->orderBy('event_date','desc')->get();
        $stat = '7';
    }

    $orderIds = array();
    foreach ($reserveOrders as $reserveOrder) {
        $orderIds[$reserveOrder->id] = $reserveOrder->id;
    }

    $reserves = array();
    if (count($orderIds) > 0) {
        $orderDbResults = Order::with('Hall')->whereIn('id', $orderIds)->get();
        foreach ($orderDbResults as $orderDbResult) {
            $reserves[$orderDbResult->id] = $orderDbResult;
        }
    }

    return View::make('mobile_layout')->nest('content', 'mobile.reserve_order_buyer',
        array('reserves'=>$reserves,'orders'=>$orderIds,'stat'=>$stat));
}));