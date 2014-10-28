<?php

Route::get('/notify/create', function(){
    $allEvents = option_notify_event();

    //如果有指定的值，则选择指定值
    $eventString = Input::get('events');
    $eventKeys = empty($eventString) ? array() : explode(',', $eventString);
    if(!empty($eventKeys)){
        $allEvents = array_intersect_key($allEvents, array_flip($eventKeys));
    }

    $object = Input::get('object');

    $channels = array_except(option_notify_channel(), NOTIFY_CHANNEL_SMS_ASYNC);

    return View::make('layout')->nest('content', 'notify.create',
        array('object' => $object, 'events' => $allEvents, 'channels' => $channels));
});

Route::get('/notify/getRecord', function(){
    $eventKey = Input::get('event');
    $object = Input::get('object');
    $channel = Input::get('channel');

    return rest_success(Notify::getRecord($eventKey, $object, $channel));
});

Route::post('/notify/send', function(){
    $eventKey = Input::get('event');
    $object = Input::get('object');
    $channel = Input::get('channel');
    $msg = Input::get('msg');

    return rest_success(Notify::sendWithBusiness($eventKey, $object, $msg, array($channel)));
});

Route::get('/notify/record', function(){
    $queries = Input::all();
    $notifyRecord = new NotifyRecord();

    $records = $notifyRecord->search($queries);

    $events = option_notify_event();
    $events[""] = '请选择通知类型';
    $channels = option_notify_channel();
    $channels[""] = '请选择通知渠道';

    return View::make('layout')->nest('content', 'notify.record',
        array('records' => $records, 'queries' => $queries, 'events' => $events, 'channels' => $channels));
});