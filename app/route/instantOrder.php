<?php
Route::get('/instant_order_mgr/{curTab}', array('before' => 'auth', function ($curTab) {
    Layout::setHighlightHeader('nav_即时订单列表（管理员侧）');

    $tabs = array(
        'payed' => array(
            'label' => '已购买订单',
            'url' => '/instant_order_mgr/payed'
        ),
        'expiring' => array(
            'label' => '将过期订单',
            'url' => '/instant_order_mgr/expiring'
        ),
        'all' => array(
            'label' => '全部订单',
            'url' => '/instant_order_mgr/all'
        ),
    );

    $queries = Input::all();
    $instantModel = new InstantOrder();

    if($curTab == 'expiring'){
        $queries['state'] = 'on_sale';
        $queries['event_date_start'] = \Carbon\Carbon::tomorrow();
        $queries['event_date_end'] = \Carbon\Carbon::tomorrow();
    }else if($curTab == 'payed'){
        $queries['state'] = 'payed';
    }

    $instants = $instantModel->search($queries);

    $states = instant_order_state_option();

    return View::make('layout')->nest('content', 'instantOrder.order_mgr',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'curTab' => $curTab, 'tabs' => $tabs));
}));

Route::get('/order_court_manage', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_场地管理（场馆侧）');

    $user = Auth::getUser();

    $hallID = Input::get('hall_id');
    $halls = $user->Halls;
    if (!$hallID && count($halls) > 0) {
        $hallID = $halls[0]->id;
    }

    $activeDate = Input::get('date');
    empty($activeDate) && $activeDate = date('Y-m-d');

    $dates = array();
    for ($i = 0; $i < MGR_WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
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

    return View::make('layout')->nest('content', 'instantOrder.order_court_manage', array(
        'halls' => $halls, 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
        'activeDate' => $activeDate, 'courts' => $courts,  'formattedInstants' => $formattedInstants,
        'instantOrders'=>$instantOrders
    ));
}));

Route::get('/instant_order_seller', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_已售场地列表（场馆侧）');

    $queries = Input::all();
    $instantModel = new InstantOrder();

    $user = Auth::getUser();
    $userID = $user['user_id'];

    $queries['seller'] = $userID;
    $queries['state'] = array('payed', 'playing', 'confirming', 'finish');

    $instants = $instantModel->search($queries);
    $states = Config::get('state.data');
    return View::make('layout')->nest('content', 'instantOrder.order_seller',
        array('instants' => $instants, 'queries' => $queries, 'states' => $states, 'userID' => $userID));
}));

Route::get('/hall_on_sale', function () {
    Layout::setHighlightHeader('nav_即时订场（用户侧）');

    $queries = Input::all();

    $curDate = date('Y-m-d');
    $queries['event_date_start'] = $curDate;
    $queries['event_date_end'] = date('Y-m-d', strtotime("+" . (WORKTABLE_SUPPORT_DAYS_LENGTH - 1). " day"));

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
        $hours[$i] = sprintf('%s时', $i, $i +1);
    }

    return View::make('layout')->nest('content', 'instantOrder.hall_on_sale',
        array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
            'halls' =>$halls, 'dates' => $dates, 'hours' => $hours));
});

Route::get('/instant_order_buyer', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_用户_即时订单列表');

    $queries = Input::all();
    $user = Auth::getUser();
    $userID = $user['user_id'];
    $queries['buyer'] = $userID;
    $queries['state'] = array_keys(
        array_except(Config::get('fsm.instant_order.states'), array('paying')));
    $instantModel = new InstantOrder();
    $instants = $instantModel->search($queries);
    $states = Config::get('state.data');

    return View::make('layout')->nest('content', 'instantOrder.order_buyer',
        array('instants' => $instants, 'states' => $states, 'userID' => $userID, 'queries' => $queries));
}));

Route::get('/order_court_buyer/{hallID?}', array('before' => 'auth', function ($hallID) {
    Layout::setHighlightHeader('nav_即时订场（用户侧）');
    $hall = Hall::findOrFail($hallID);

    Layout::appendBreadCrumbs($hall->name);

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

    return View::make('layout')->nest('content', 'instantOrder.order_court_buyer', array(
        'halls' => array($hall), 'dates' => $dates, 'hallID'=>$hallID, 'weekdayOption' => weekday_option(),
        'activeDate' => $activeDate, 'courts' => $courts,  'formattedInstants' => $formattedInstants,
        'loginUserId' => Auth::getUser()->user_id, 'instantOrders'=>$instantOrders
    ));

}));


Route::get('/fsm-operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $instantOrder = InstantOrder::findOrFail($id);
    $fsm = new InstantOrderFsm($instantOrder);
    $fsm->apply($operate);
    $url = URL::previous();
    return $redirect = Redirect::to($url);

}));

Route::post('/instantOrder/batchOperate', array('before' => 'auth',function(){
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

Route::post('/instantOrder/batchBuy', array('before' => 'auth',function(){
    $instantOrderIdString = Input::get('instant_order_ids');
    $instantOrderIds = explode(',', $instantOrderIdString);

    DB::beginTransaction();
    try{
        $manager = new InstantOrderManager();
        $result = $manager->batchBuy($instantOrderIds);
        DB::commit();
        return rest_success($result);
    }catch (Exception $e){
        DB::rollBack();
        throw $e;
    }
}));

Route::post('/instantOrder/batchPay', array('before' => 'auth',function(){
    $instantOrderIdString = Input::get('instant_order_ids');
    $instantOrderIds = explode(',', $instantOrderIdString);

    DB::beginTransaction();
    try{
        $manager = new InstantOrderManager();
        $result = $manager->batchPay($instantOrderIds);
        DB::commit();
        return rest_success($result);
    }catch (Exception $e){
        DB::rollBack();
        throw $e;
    }
}));