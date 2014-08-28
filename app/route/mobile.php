<?php

View::creator('mobile_layout', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
});

View::creator('format.mobile.footer', function ($view) {
    if (Auth::check()) {
        $user = Auth::getUser();
        $userID = $user->user_id;
        $instantModel = new InstantOrder();
        $queries['buyer'] = $userID;
        $queries['state'] = 'paying';
        $payingInstants = $instantModel->search($queries);
        $paying = $payingInstants->count();
        $queries['state'] = 'payed';
        $payedInstants = $instantModel->search($queries);
        $payed = $payedInstants->count();
        if($payed ==0 && $paying==0 ){
            $isActive = false;
        }
        else{$isActive =true;}
        $view->with('isActive', $isActive);
    }
});

Route::get('/mobile_home/instant',function(){
    $orders = array(
        'reserve' => array(
            'label' => '预约场地',
            'url' => '/mobile_home/reserve',

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
 return View::make('mobile_layout')->nest('content','mobile.home',
     array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
         'halls' =>$halls, 'dates' => $dates, 'hours' => $hours,'orders'=>$orders,'curOrder'=>$curOrder));

});

Route::get('/mobile_home/reserve/{curType?}',function($curType){
    $orders = array(
        'reserve' => array(
            'label' => '预约场地',
            'url' => '/mobile_home/reserve',

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
            'label' => '附近场馆',
            'url' => '/mobile_home/reserve/ordered',
        ),
    );


    $curOrder = 'reserve';

    return View::make('mobile_layout')->nest('content','mobile.home',
        array('orders'=>$orders,'curOrder'=>$curOrder,'curType'=>$curType,'types'=>$types));
});


Route::get('/mobile_recommend_hall',function(){


});

Route::get('/mobile_nearby_hall',function(){


});

Route::get('/mobile_ordered_hall',array('before'=>'auth',function(){


}));

Route::get('/mobile_buyer',array('before'=>'weixin',function(){
    $user = Auth::getUser();
    $userID = $user['user_id'];
    $userAccount = UserAccount::where('user_id','=',$userID)->where('purpose','=',1)->first();
    $userPoint = UserAccount::where('user_id','=',$userID)->where('purpose','=',2)->first();

    $instantModel = new InstantOrder();
    $queries['buyer'] = $userID;
    $queries['state'] = 'paying';
    $payingInstants = $instantModel->search($queries);
    $paying = $payingInstants->count();
    $queries['state'] = 'payed';
    $payedInstants = $instantModel->search($queries);
    $payed = $payedInstants->count();
    return View::make('mobile_layout')->nest('content','mobile.mobile_buyer',
        array('user'=>$user,'account'=>$userAccount,'point'=>$userPoint,'paying'=>$paying,'payed'=>$payed));

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
                }
                else{
                    if($app instanceof RelationUserApp){
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

Route::get('/mobile_buyer_order',array('before'=>'weixin',function(){
    $queries = Input::all();

    $user = Auth::getUser();
    $userID = $user['user_id'];
    $instantModel = new InstantOrder();
    $queries['buyer'] = $userID;
    $instants = $instantModel->search($queries);


    return View::make('mobile_layout')->nest('content','mobile.order_buyer',
        array('user'=>$user,'instants'=>$instants));

}));
