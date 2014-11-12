<?php
Route::get('/reserve_order_mgr/{curTab}', array('before' => 'auth', function ($curTab) {
    Layout::setHighlightHeader('nav_预约订单列表（管理员）');
    $tabs = array(
        'book_pending' => array(
            'label' => '待处理订单',
            'url' => '/reserve_order_mgr/book_pending'
        ),
        'all' => array(
            'label' => '全部订单',
            'url' => '/reserve_order_mgr/all',
        ),
    );

    $queries = Input::all();
    if ($curTab == 'book_pending') {
        $queries['stat'] = 0;
    } else if (isset($queries['stat']) && strlen($queries['stat']) > 0) {
        $queries['stat'] = intval($queries['stat']);
    }

    $reserveModel = new ReserveOrder();
    $reserves = $reserveModel->search($queries);
    adjustTimeStamp($reserves);

    $states = reserve_order_status_option();
    return View::make('layout')->nest('content', 'reserveOrder.order_mgr',
        array('reserves' => $reserves, 'queries' => $queries, 'states' => $states, 'curTab' => $curTab, 'tabs' => $tabs));
}));

Route::get('/reserve/operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $reserveOrder = ReserveOrder::findOrFail($id);
    $fsm = new ReserveOrderFsm($reserveOrder);
    $result = $fsm->apply($operate);
    return rest_success($result);
}));

Route::any('reserveOrder/pay', array('before' => 'auth', function () {
    $reserveOrderIdString = Input::get('reserve_order_ids');
    $reserveOrderIds = explode(',', $reserveOrderIdString);

    DB::beginTransaction();
    try {
        $manager = new ReserveOrderManager();
        $result = $manager->batchPay($reserveOrderIds);
        DB::commit();
        return rest_success($result);
    } catch (Exception $e) {
        DB::rollBack();
        throw $e;
    }
}));

/**
 * 创建预约订单
 */
Route::any('/reserve/create', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_新增预约订单（管理员）');
    $order = Input::only(array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));
    return View::make('layout')->nest('content', 'reserveOrder.create_mgr', array('order' => $order));
}));

/**
 * 计算金额
 */
Route::any('/reserve/calculate', function () {
    $order = Input::only(array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));
    //计算结果值
    $reserveOrder = new ReserveOrderManager();
    $result = $reserveOrder->calculate($order);
    //返回结果
    return rest_success($result);
});

/**
 * 更新订单
 */
Route::any('/reserve/modify/{orderId}', array('before' => 'auth', function($orderId){
    $order = ReserveOrder::findOrFail($orderId);
    Layout::setHighlightHeader('nav_预约订单一级列表');
    Layout::appendBreadCrumbs('修改订单');

    return View::make('layout')->nest('content', 'reserveOrder.create_mgr',
        array('order' => $order));
}));

Route::post('/reserve/save', array('before' => 'auth', function(){
    $orderId = Input::get('id');

    $orderInput = Input::only(array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));
    //计算价格
    $reserveOrder = new ReserveOrderManager();
    $result = $reserveOrder->calculate($orderInput);

    if(!empty($orderId)){
        ReserveOrder::whereId($orderId)->update($orderInput);
        $order = ReserveOrder::findOrFail($orderId);
    }else{
        //生成订单
        $orderModel = new ReserveOrder();
        $order = $orderModel->create($orderInput);
        //发送消息
        Notify::sendWithBusiness(NOTIFY_TYPE_ORDER_NOTICE, $order->id);
    }
    $result['order'] = $order;

    return rest_success($result);
}));

/**
 * 预约订单详情
 */
Route::get('/reserve/detail/{orderId}', array('before' => 'auth', function($orderId){
    $order = ReserveOrder::findOrFail($orderId);

    $user = User::remember(CACHE_HOUR)->findOrFail($order['user_id']);
    $user->balance = cache_balance($order['user_id']);
    adjustTimestampForOneModel($user);
    $order['user'] = $user->toArray();

    $hall = Hall::remember(CACHE_HOUR)->find($order['hall_id']);
    adjustTimestampForOneModel($hall);
    $order['hall'] = $hall->toArray();

    return View::make('layout')->nest('content', 'reserveOrder.detail_mgr', array('order' => $order));
}));