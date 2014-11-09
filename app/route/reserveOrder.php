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
    if($curTab == 'book_pending'){
        $queries['stat'] = 0;
    }else if(isset($queries['stat']) && strlen($queries['stat']) > 0){
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

Route::any('reserveOrder/pay', array('before' => 'auth', function(){
    $reserveOrderIdString = Input::get('reserve_order_ids');
    $reserveOrderIds = explode(',', $reserveOrderIdString);

    DB::beginTransaction();
    try{
        $manager = new ReserveOrderManager();
        $result = $manager->batchPay($reserveOrderIds);
        DB::commit();
        return rest_success($result);
    }catch (Exception $e){
        DB::rollBack();
        throw $e;
    }
}));