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
    $orders = array(
        'reserve' => array(
            'label' => '预约场地',
            'url' => '/mobile_home/reserve/recommend',

        ),
        'instant' => array(
            'label' => '即时场地',
            'url' => '/mobile_home/instant',

        ),
    );
    $curOrder = 'instant';
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
    return View::make('mobile_layout')->nest('content', 'mobile.home',
        array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
            'halls' => $halls, 'dates' => $dates, 'hours' => $hours, 'orders' => $orders, 'curOrder' => $curOrder));

});

Route::get('/mobile_home/reserve/{curType?}', function ($curType) {
    $orders = array(
        'reserve' => array(
            'label' => '预约场地',
            'url' => '/mobile_home/reserve/recommend',

        ),
        'instant' => array(
            'label' => '即时场地',
            'url' => '/mobile_home/instant',

        ),
    );
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
    $curOrder = 'reserve';
    if ($curType == 'recommend') {
        $HallActive = HallActive::where('type', '=', 1)->get();
        $Halls = array();
        foreach ($HallActive as $Hall) {
            $array = Hall::where('id', '=', $Hall['hall_id'])->get();
            $array = $array->toArray();
            $Halls = array_merge($Halls, $array);
        }
    } elseif ($curType == 'nearby') {
        $appUserID = Input::get('app_user_id');
        $time = strtotime(date('Y-m-d', time()));
        $location = WXLocation::where('app_user_id', '=', $appUserID)->where('creattime', '>', $time)->orderBy('creattime', 'desc')->first();
        if ($location) {
            $lat = $location->lat;
            $lon = $location->lon;
            $hallNearby = DB::select('select `hall_id`,`lon`,`lat`,ACOS(SIN((" . $lat . " * 3.1415) / 180 ) * SIN((`lat` * 3.1415) / 180 ) + COS((" . $lat . " * 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS((" . $lon . " * 3.1415) / 180 - (`long` * 3.1415) / 180 ) ) * 6380 as description from `gt_hall_tiny` as a join `gt_hall_map` as b on a.id=b.`hall_id` where
                          a.`stat` =2 and
                          b.`lat` > " . $lat . "-1 and
                          b.`lat` < " . $lat . "+1 and
                          b.`long` > " . $lon . "-1 and
                          b.`long` < " . $lon . "+1 order by description asc limit 7', array());
            $Halls = array();
            foreach ($hallNearby as $Hall) {
                $array = Hall::where('id', '=', $Hall['hall_id'])->get();
                $array = $array->toArray();
                $Halls = array_merge($Halls, $array);
            }
        } else {
            // 没有开启地理位置服务
        }
    } elseif ($curType == 'ordered') {
        $appUserID = Input::get('app_user_id');
        $app = RelationUserApp::where('app_user_id', '=', $appUserID)->first();
        if ($app) {
            $userID = $app->user_id;
            $hallOrdered = ReserveOrder::where('user_id', '=', $userID)->orderBy('event_date', 'desc')->select( 'hall_id')->distinct()->get();

            if (!isset($hallOrdered)) {
                //还没定过场地
            } else {
                $Halls = array();
                foreach ($hallOrdered as $Hall) {
                    $array = Hall::where('id', '=', $Hall->hall_id)->distinct()->get();
                    $array = $array->toArray();
                    $Halls = array_merge($Halls, $array);
                }
            }
        }
        else{
            //跳转绑定
        }
    }

foreach($Halls as $key=> $hall){
    $temp = HallPrice::where('hall_id','=',$hall['id'])->get();

    $temp = $temp->toArray();
    $Halls[$key]['price'] = $temp;
}
    //print_r($Halls);exit;
    return View::make('mobile_layout')->nest('content', 'mobile.home',
        array('orders' => $orders, 'curOrder' => $curOrder, 'curType' => $curType, 'types' => $types,'halls'=>$Halls,
        ));
});

Route::get('/mobile_buyer', array('before' => 'weixin', function () {
    $user = Auth::getUser();
    $userID = $user['user_id'];
    $userAccount = UserAccount::where('user_id', '=', $userID)->where('purpose', '=', 1)->first();
    $userPoint = UserAccount::where('user_id', '=', $userID)->where('purpose', '=', 2)->first();

    $instantModel = new InstantOrder();
    $queries['buyer'] = $userID;
    $queries['state'] = 'paying';
    $payingInstants = $instantModel->search($queries);
    $paying = $payingInstants->count();
    $queries['state'] = 'payed';
    $payedInstants = $instantModel->search($queries);
    $payed = $payedInstants->count();
    return View::make('mobile_layout')->nest('content', 'mobile.mobile_buyer',
        array('user' => $user, 'account' => $userAccount, 'point' => $userPoint, 'paying' => $paying, 'payed' => $payed));

}));


Route::get('/mobile_bond', function () {
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
    $queries = Input::all();

    $user = Auth::getUser();
    $userID = $user['user_id'];
    $instantModel = new InstantOrder();
    $queries['buyer'] = $userID;
    $instants = $instantModel->search($queries);


    return View::make('mobile_layout')->nest('content', 'mobile.order_buyer',
        array('user' => $user, 'instants' => $instants));

}));

Route::get('/hall_reserve',function(){

    return View::make('mobile_layout')->nest('content', 'mobile.hall_reserve');
});

Route::get('/mobile_court_buyer/{hallID?}', array('before' => 'auth', function($hallID){
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

    return View::make('mobile.court_buyer', array(
        'halls' => array($hall), 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
        'activeDate' => $activeDate, 'courts' => $courts,  'formattedInstants' => $formattedInstants,
        'loginUserId' => Auth::getUser()->user_id, 'instantOrders'=>$instantOrders, 'noMoney'=>array(
            'needPay'=>0, 'balance'=>0, 'needRecharge'=>0,'adviseForwardUrl'=>''
        )
    ));
}));