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
    } else if (isset($queries['stat'])) {
        if (strlen($queries['stat']) > 0) {
            $queries['stat'] = intval($queries['stat']);
        }else{
            unset($queries['stat']);
        }
    }
    //默认不展示预订失败的单子
    empty($queries['stat']) && $queries['stat_ne'] = RESERVE_STAT_FAILED;

    $reserveModel = new ReserveOrder();
    $reserves = $reserveModel->search($queries);
    $publishedHalls = option_published_halls();

    $states = reserve_order_status_option();
    return View::make('layout')->nest('content', 'reserveOrder.order_mgr',
        array('halls' => $publishedHalls, 'reserves' => $reserves, 'queries' => $queries, 'states' => $states, 'curTab' => $curTab, 'tabs' => $tabs));
}));

//我的预约订单列表
Route::get('/reserve/mgr/list', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_预约订单列表（管理员）');

    $queries = Input::all();
    return View::make('layout')->nest('content', 'reserveOrder.frontend.list', array('queries' => $queries));
}));

Route::get('/reserve/operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $reserveOrder = ReserveOrder::findOrFail($id);
    $fsm = new ReserveOrderFsm($reserveOrder);
    $result = $fsm->apply($operate);
    return Redirect::to(URL::previous());
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

//前端用户创建预约订单
Route::get('/reserve/frontend/create', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_用户_场馆一览');
    Layout::appendBreadCrumbs('预订场地');
    $order = Input::only(array('hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));

    $user = Auth::getUser();
    $order['user'] = $user->toArray();

    return View::make('layout')->nest('content',
        'reserveOrder.frontend.create', array('order' => $order, 'halls' => option_published_halls()));
}));

//我的预约订单列表
Route::get('/reserve/frontend/list', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_用户_预约订单列表');

    $queries = Input::all();
    return View::make('layout')->nest('content', 'reserveOrder.frontend.list', array('queries' => $queries));
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
Route::any('/reserve/modify/{orderId}', array('before' => 'auth', function ($orderId) {
    $order = ReserveOrder::findOrFail($orderId);
    Layout::setHighlightHeader('nav_预约订单一级列表');
    Layout::appendBreadCrumbs('修改订单');

    return View::make('layout')->nest('content', 'reserveOrder.create_mgr',
        array('order' => $order));
}));

Route::post('/reserve/save', array('before' => 'auth', function () {
    $orderId = Input::get('id');

    $orderInput = Input::only(array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));
    $orderInput['event_date'] = strtotime(date('Y-m-d', $orderInput['event_date']));
    //计算价格
    $reserveOrder = new ReserveOrderManager();
    $result = $reserveOrder->calculate($orderInput);

    if (!empty($orderId)) {
        ReserveOrder::whereId($orderId)->update($orderInput);
        $order = ReserveOrder::findOrFail($orderId);
    } else {
        //生成订单
        $orderModel = new ReserveOrder();
        $order = $orderModel->create($orderInput);
        if(current_role() != ROLE_MGR){
            //发送消息
            Notify::sendWithBusiness(NOTIFY_TYPE_ORDER_NOTICE, $order->id);
        }
    }
    $result['order'] = $order;

    return rest_success($result);
}));

/**
 * 预约订单详情
 */
Route::get('/reserve/detail/{orderId}', array('before' => 'auth', function ($orderId) {
    $order = ReserveOrder::findOrFail($orderId);
    Layout::setHighlightHeader('nav_预约订单一级列表');
    Layout::appendBreadCrumbs('订单详情');

    $user = User::remember(CACHE_HOUR)->findOrFail($order['user_id']);
    $user->balance = cache_balance($order['user_id']);
    $order['user'] = $user->toArray();

    $hall = Hall::remember(CACHE_HOUR)->find($order['hall_id']);
    $order['hall'] = $hall->toArray();

    return View::make('layout')->nest('content', 'reserveOrder.detail_mgr', array('order' => $order));
}));

Route::get('/reserve/search', function () {
    $perPage = Input::get('per_page', 20);
    $queries = Input::all();

    if (current_role() != ROLE_MGR) {
        $queries['user_id'] = user_id();
    }

    if (isset($queries['stat']) && strlen($queries['stat']) <= 0) {
        unset($queries['stat']);
    };

    $reserveModel = new ReserveOrder();
    $reserveOrders = $reserveModel->search($queries, $perPage, Input::get('relations', ''));

    return rest_success($reserveOrders->toArray());
});