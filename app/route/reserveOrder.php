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

Route::get('/reserveOrder/operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
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

Route::get('/reserve/create', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_新增预约订单（管理员）');
    $order = Input::only(array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'));

    if (!empty($order['user_id'])) {
        $order['user'] = User::find($order['user_id']);
        adjustTimestampForOneModel($order['user']);
    }
    if (!empty($order['hall_id'])) {
        $order['hall'] = Hall::find($order['hall_id']);
        adjustTimestampForOneModel($order['hall']);
    }

    return View::make('layout')->nest('content', 'reserveOrder.create_mgr',
        array('order' => $order));
}));

Route::post('/reserve/create/{userId}/{hallId}/{eventDate}/{startTime}/{endTime}/{courtNum}',
    function ($userId, $hallId, $eventDate, $startTime, $endTime, $courtNum) {
        $hallMarkets = HallMarket::with('HallPrice')->whereHallId($hallId)->get();

        $user = User::findOrFail($userId);
        adjustTimestampForOneModel($user);
        $eventDate = strtotime(date('Y-m-d', $eventDate));
        $holiday = LegalHolidays::whereDate($eventDate)->first();
        $week = intval(date('N', $eventDate));

        //查找指定日期所在的市场
        $dateHitMarkets = array();
        if ($holiday instanceof LegalHolidays) {
            foreach ($hallMarkets as $hallMarket) {
                if ($hallMarket->type == $holiday->type) {
                    $dateHitMarkets[] = $hallMarket;
                }
            }
        } else {
            foreach ($hallMarkets as $hallMarket) {
                if (($hallMarket->start_week <= $week) && ($hallMarket->end_week >= $week)) {
                    $dateHitMarkets[] = $hallMarket;
                }
            }
        }

        //查找指定时间段所在的市场
        $hourHitMarkets = array();
        for ($timeIndex = $startTime; $timeIndex < $endTime; $timeIndex++) {
            $existed = false;
            foreach ($dateHitMarkets as $market) {
                if ($market['start'] <= $timeIndex && $market['end'] >= $timeIndex + 1) {
                    $hourHitMarkets[] = $market;
                    $existed = true;
                }
            }
            if (!$existed) {
                throw new Exception(sprintf("未找见指定的时间段%s-%s", $timeIndex, $timeIndex + 1));
            }
        }

        $costs = array('market' => 0, 'member' => 0, 'vip' => 0, 'purchase' => 0);
        foreach ($hourHitMarkets as $market) {
            $costs['market'] += $market->HallPrice->market * $courtNum;
            $costs['member'] += $market->HallPrice->member * $courtNum;
            $costs['vip'] += $market->HallPrice->vip * $courtNum;
            $costs['purchase'] += $market->HallPrice->purchase * $courtNum;
        }

        $cost = $user->privilege == PRIVILEGE_GOLD ? $costs['vip'] : $costs['member'];
        $orderData = array('user_id' => $userId, 'hall_id' => $hallId, 'event_date' => $eventDate,
            'start_time' => $startTime, 'end_time' => $endTime, 'cost' => $cost, 'court_num' => $courtNum);

        $reference = array('hall_markets' => $hallMarkets, 'date_hit_markets' => $dateHitMarkets, 'week' => $week,
            'costs' => $costs, 'holiday' => $holiday, 'hour_hit_markets' => $hourHitMarkets);

        $preview = Input::get('preview');
        if (!$preview) {
            $order = new ReserveOrder();
            $orderData = $order->create($orderData);
        }
        $reference['order'] = $orderData;

        return rest_success($reference);
    });