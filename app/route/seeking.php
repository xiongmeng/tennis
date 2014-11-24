<?php
Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT']), function () {
    Route::get('/seeking/list', function () {
        MobileLayout::$activeService = 'reserve';
        $queries = Input::all();
        return View::make('mobile_layout_hall')->nest('content', 'mobile.seeking_list',
            array('queries' => $queries));
    });

    Route::get('/seeking/detail/{id}', function ($id) {
        $seeking = Seeking::with('Hall')->findOrFail($id);
        $states = option_seeking_state();

        $orders = SeekingOrder::with('Joiner')->whereSeekingId($id)->get();
        $orderStates = option_seeking_order_state();

        MobileLayout::$title = sprintf("约球详情（id：%s） %s", $seeking->id, $states[$seeking->state]);

        return View::make('mobile_layout')->nest('content', 'mobile.seeking_detail',
            array('seeking' => $seeking, 'states' => $states, 'orders' => $orders, 'orderStates' => $orderStates));
    });
});

Route::get('/seeking/create', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_新建约球');
    $seeking = Input::only('hall_id');

    $publishedHalls = option_published_halls();
    return View::make('layout')->nest('content', 'seeking.create_mgr',
        array('halls' => $publishedHalls, 'seeking' => $seeking));
}));

Route::get('/seeking/modify/{id}', array('before' => 'auth', function ($id) {
    Layout::setHighlightHeader('nav_约球一级菜单');
    Layout::appendBreadCrumbs('修改约球信息');

    $seeking = Seeking::findOrFail($id);
    $publishedHalls = option_published_halls();
    return View::make('layout')->nest('content', 'seeking.create_mgr',
        array('halls' => $publishedHalls, 'seeking' => $seeking));
}));

Route::post('/seeking/save', array('before' => 'auth', function () {
    $seekingInput = Input::only(array('event_date', 'start_hour', 'end_hour', 'hall_id', 'court_num',
        'tennis_level', 'sexy', 'on_sale', 'store', 'personal_cost', 'content', 'comment'));
    isset($seekingInput['event_date']) && $seekingInput['event_date'] = date('Y-m-d', strtotime($seekingInput['event_date']));
    $id = Input::get('id');
    if (empty($id)) {
        $seekingInput['state'] = SEEKING_STATE_CLOSED;
        $seekingInput['creator'] = user_id();
        $seekingCreated = Seeking::create($seekingInput);
        return rest_success($seekingCreated);
    } else {
        $seeking = Seeking::findOrFail($id);
        $seekingFsm = new SeekingFsm($seeking);
        if ($seekingFsm->can('modify')) {
            $seeking->update($seekingInput);
        } else {
            throw new Exception('当前状态不支持修改');
        }
        return rest_success($seeking);
    }
}));

Route::any('/seeking/list', array('before' => 'auth', function () {
    Layout::setHighlightHeader('nav_约球列表');

    $queries = Input::all();

    $seeking = new Seeking();
    $seekingList = $seeking->search($queries);

    $states = option_seeking_state();
    return View::make('layout')->nest('content', 'seeking.list_mgr',
        array('seekingList' => $seekingList, 'queries' => $queries, 'states' => $states));
}));

Route::get('/seeking/search', function () {
    $queries = Input::all();
    $perPage = Input::get('perPage', 20);

    $seeking = new Seeking();
    $seekingList = $seeking->search($queries, $perPage);
    return rest_success($seekingList->toArray());
});

Route::get('/seeking/operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $seeking = Seeking::findOrFail($id);
    $fsm = new SeekingFsm($seeking);
    $fsm->apply($operate);

    if (Request::ajax()) {
        return rest_success($seeking);
    } else {
        return Redirect::to(URL::previous());
    }
}));

Route::get('/seeking/increase/{id}/{num}', array('before' => 'auth', function ($id, $num) {
    $seeking = Seeking::findOrFail($id);
    $fsm = new SeekingFsm($seeking);
    $fsm->increase($num);

    if (Request::ajax()) {
        return rest_success($seeking);
    } else {
        return Redirect::to(URL::previous());
    }
}));

Route::get('/seeking/decrease/{id}/{num}', array('before' => 'auth', function ($id, $num) {
    $seeking = Seeking::findOrFail($id);
    $fsm = new SeekingFsm($seeking);
    $fsm->decrease($num);

    if (Request::ajax()) {
        return rest_success($seeking);
    } else {
        return Redirect::to(URL::previous());
    }
}));

Route::get('/seeking/detail/{id}', function ($id) {
    $seeking = Seeking::with('Hall')->findOrFail($id);
    $states = option_seeking_state();

    $orders = SeekingOrder::with('Joiner')->whereSeekingId($id)->get();
    $orderStates = option_seeking_order_state();

    return View::make('layout')->nest('content', 'seeking.detail_mgr',
        array('seeking' => $seeking, 'states' => $states, 'orders' => $orders, 'orderStates' => $orderStates));
});

Route::get('/seeking/join/{id}', array('before' => 'auth', function ($id) {
    $seeking = Seeking::findOrFail($id);
    //仅仅是为了代码提示
    $seeking instanceof Seeking && 1;

    $fsm = new SeekingFsm($seeking);
    if ($fsm->can('join')) {
        $order = SeekingOrder::create(array('state' => SEEKING_ORDER_STATE_DISPOSING, 'seeking_id' => $id,
            'seeker' => $seeking->creator, 'joiner' => user_id(), 'cost' => $seeking->personal_cost));

        if (Request::ajax()) {
            return rest_success($order);
        } else {
            return Redirect::to(URL::previous());
        }
    }

    throw new Exception('当前状态不能参加！');
}));

Route::get('/seeking/order/operate/{id?}/{operate?}', array('before' => 'auth', function ($id, $operate) {
    $seekingOrder = SeekingOrder::findOrFail($id);
    $fsm = new SeekingOrderFsm($seekingOrder);
    $fsm->apply($operate);
    if (Request::ajax()) {
        return rest_success($seekingOrder);
    } else {
        return Redirect::to(URL::previous());
    }
}));


