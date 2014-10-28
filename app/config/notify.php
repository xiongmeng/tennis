<?php
function contactReserveOrderInfo(ReserveOrder $order)
{
    $hall = cache_hall($order->hall_id);
    return sprintf("%s%s日%s点-%s点的%s%s片场地，计%s元", $hall->name, date("Y-m-d", $order->event_date),
        $order->start_time, $order->end_time, weekday($order->event_date), $order->court_num, $order->cost);
}

function contactFinanceInfo(User $user)
{
    return sprintf('您目前的账户余额为%s元，积分为%s分。', balance($user->user_id), points($user->user_id));
}

function get_who_from_user_and_channel($user_id, $channel){
    $user = cache_user($user_id);
    switch($channel){
        case NOTIFY_CHANNEL_SMS_SYNC:
        case NOTIFY_CHANNEL_SMS_ASYNC:
            return $user->telephone;
        case NOTIFY_CHANNEL_WX_SYNC:
            $wxApp = RelationUserApp::whereUserId($user->user_id)->whereAppId(APP_WE_CHAT)->first();
            return $wxApp ? $wxApp->app_user_id : null;
        default:
            return null;
    }
}

return array(
    'events' => array(
        //预约订单取消成功
        NOTIFY_TYPE_ORDER_CANCEL => array(
            'who' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    return get_who_from_user_and_channel($order->user_id, $channel);
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    return sprintf("您预订的场地已经取消成功，订单号%s（%s）。%s元已返还到您的账户中。",
                        $order->id, contactReserveOrderInfo($order), $order->cost);
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_ASYNC),
            'title' => '预约订单已取消',
        ),
        //预约订单支付成功
        NOTIFY_TYPE_ORDER_PAYED => array(
            'who' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    return get_who_from_user_and_channel($order->user_id, $channel);
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    $hall = cache_hall($order->hall_id);
                    return sprintf("您预订的场地已经支付成功，订单号%s（%s）。场馆联系电话%s",
                        $order->id, contactReserveOrderInfo($order), $hall->telephone);
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_ASYNC),
            'title' => '预约订单支付成功',
        ),
        //预约订单待支付
        NOTIFY_TYPE_ORDER_UNPAY => array(
            'who' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    return get_who_from_user_and_channel($order->user_id, $channel);
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    $hall = cache_hall($order->hall_id);
                    return sprintf("您预订了%s,请%s分钟内登录网球通/微信完成支付，或致电4000665189授权网球通客服人员帮您完成网上支付。场馆联系电话%s",
                        contactReserveOrderInfo($order), RESERVE_EXPIRE_TIME, $hall->telephone);
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_ASYNC),
            'title' => '预约订单待支付',
        ),
        //网站创建预约订单后的管理员短信通知
        NOTIFY_TYPE_ORDER_NOTICE => array(
            'who' => function ($object_id, $channel) {
                    return '15210489872';
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_reserve_order($object_id);
                    $booker = cache_user($order->user_id);

                    $privilegeOption = option_user_privilege();

                    return sprintf("新订单%s，%s。%s，%s，%s，%s", $order->id, contactReserveOrderInfo($order),
                        $booker->nickname, $privilegeOption[$booker->privilege], $booker->telephone, contactFinanceInfo($booker));
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_ASYNC),
            'title' => '新预约订单通知（15210489872）',
        ),
        //即时订单购买成功后用户的消息
        NOTIFY_TYPE_USER_INSTANT_ORDER_PAYED => array(
            'who' => function($object_id, $channel){
                    $order = cache_instant_order($object_id);
                    return get_who_from_user_and_channel($order->buyer, $channel);
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_instant_order($object_id);
                    $buyer = cache_user($order->buyer);
                    $hall = $order->Hall;
                    return sprintf("您预订的场地已经支付成功，订单号%s（%s%s日%s点-%s点%s号场地）。场馆联系电话：%s。%s",
                        $order->id, $hall->name, substr($order->event_date, 0, 10), $order->start_hour,
                        $order->end_hour, $order->court_number, $hall->telephone, contactFinanceInfo($buyer));
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_ASYNC, NOTIFY_CHANNEL_WX_SYNC),
            'title' => '即时订单购买成功（用户侧）'
        ),
        //即时订单购买成功后场馆侧的消息提醒
        NOTIFY_TYPE_HALL_INSTANT_ORDER_SOLD => array(
            'who' => function($object_id, $channel){
                    $order = cache_instant_order($object_id);
                    $user = cache_user($order->seller);
                    return $user->receive_sms_telephone;
                },
            'msg' => function ($object_id, $channel) {
                    $order = cache_instant_order($object_id);
                    $hall = $order->Hall;
                    return sprintf("售出%s%s日%s点-%s点%s号场地，订单号%s。", $hall->name, substr($order->event_date, 0, 10),
                        $order->start_hour, $order->end_hour, $order->court_number, $order->id);
                },
            'channels' => array(NOTIFY_CHANNEL_SMS_SYNC),
            'title' => '即时订单售卖成功（场馆侧）'
        )
    ),

    'channels' => array(
        NOTIFY_CHANNEL_SMS_ASYNC => array(
            'send' => function ($msg, $telephone, $token = null) {
                    Sms::sendAsync($telephone, $msg, $token);
                },
            'title' => '短信，有延迟'
        ),
        NOTIFY_CHANNEL_SMS_SYNC => array(
            'send' => function($msg, $telephone, $token = null){
                    Sms::sendSync($telephone, $msg, $token);
                },
            'title' => '短信发送'
        ),
        NOTIFY_CHANNEL_WX_SYNC => array(
            'send' => function($msg, $openid){
                    $client = new \Cooper\Wechat\WeChatClient();
                    $client->sendTextMsg($openid, $msg);
                    return \Cooper\Wechat\WeChatClient::error();
                },
            'title' => '微信发送'
        )
    )
);
