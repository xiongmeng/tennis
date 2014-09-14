<?php
function contactReserveOrderInfo(ReserveOrder $order)
{
    $hall = $order->Hall;
    if (empty($hall)) {
        throw new Exception('please ensure hall is loaded!');
    }

    return sprintf("%s%s日%s点-%s点的%s%s片场地，计%s元", $hall->name, date("Y-m-d", $order->event_date),
        $order->start_time, $order->end_time, weekday($order->event_date), $order->court_num, $order->cost);
}

function contactFinanceInfo(User $user)
{
    return sprintf('您目前的账户余额为%s元，积分为%s分。', balance($user->user_id), points($user->user_id));
}

return array(
    'events' => array(
        //网站创建预约订单后的短信处理流程
        'mgr_reserve_order_created' => array(
            'users' => function (ReserveOrder $reserveOrder, NotifyChunkPack $chunk) {
                    return User::whereUserId(889295)->get();
                },
            'object' => function ($objectId) {
                    return ReserveOrder::with(array('Hall', 'User'))->find($objectId);
                },
            'msg' => function (ReserveOrder $reserveOrder, User $user, $channel) {
                    $booker = $reserveOrder->User;
                    $privilegeOption = option_user_privilege();

                    $msg = sprintf("新订单%s，%s。%s，%s，%s，%s", $reserveOrder->id, contactReserveOrderInfo($reserveOrder),
                        $booker->nickname, $privilegeOption[$booker->privilege], $booker->telephone, contactFinanceInfo($booker));

                    return $msg;
                }
        ),
        //即时订单购买成功后用户的消息
        'user_instant_order_payed' => array(
            'users' => function (InstantOrder $order, NotifyChunkPack $chunk) {
                    return User::whereUserId($order->buyer)->get();
                },
            'object' => function ($objectId) {
                    return InstantOrder::with(array('Hall', 'User'))->find($objectId);
                },
            'msg' => function (InstantOrder $order, User $buyer, $channel) {
                    $hall = $order->Hall;
                    return sprintf("您预订的场地已经支付成功，订单号%s（%s%s日%s点-%s点%s号场地）。场馆联系电话：%s。%s",
                        $order->id,  $hall->name, substr($order->event_date, 0, 10), $order->start_hour,
                        $order->end_hour, $order->court_number, $hall->telephone, contactFinanceInfo($buyer));
                }
        ),
        //即时订单购买成功后场馆侧的消息提醒
        'hall_instant_order_sold' => array(
            'users' => function (InstantOrder $order, NotifyChunkPack $chunk) {
                    return User::whereUserId($order->seller)->get();
                },
            'object' => function ($objectId) {
                    return InstantOrder::with(array('Hall'))->find($objectId);
                },
            'msg' => function (InstantOrder $order, User $seller, $channel) {
                    $hall = $order->Hall;
                    return sprintf("售出%s%s日%s点-%s点%s号场地，订单号%s。", $hall->name, substr($order->event_date,0,10),
                        $order->start_hour, $order->end_hour, $order->court_number, $order->id);
                }
        )
    ),

    'channels' => array(
        'sms' => array('send' => function ($msg, User $user) {
                if($user->telephone){
                    Sms::sendAsync($user->telephone, $msg, $user->user_id);
                }
            })
    )
);
