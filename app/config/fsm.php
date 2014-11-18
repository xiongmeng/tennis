<?php

return array(
    'instant_order' => array(
        'class' => 'Document',
        'states' => array(
            'draft' => array( //草稿
                'type' => Finite\State\StateInterface::TYPE_INITIAL,
                'properties' => array('deletable' => true, 'editable' => true),
            ),
            'waste' => array( //废弃的，在草稿状态超过了当前时间的订单，-这个操作有脚本批量处理
                'type' => Finite\State\StateInterface::TYPE_FINAL,
            ),
            'on_sale' => array( //待售
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'paying' => array( //等待支付
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'payed' => array( //支付成功
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'playing' => array( //打球中
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'confirming' => array( //等待确认
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'canceled' => array( //已取消-这个状态没有用，在购买后点取消会重回上架状态
                'type' => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'expired' => array( //过期未售出
                'type' => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'terminated' => array( //已终止
                'type' => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'finish' => array( //完成
                'type' => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array('printable' => true),
            )
        ),
        'transitions' => array(
            'online' => array('from' => array('draft'), 'to' => 'on_sale'), //seller
            'buy' => array('from' => array('on_sale'), 'to' => 'paying'), //buyer
            'offline' => array('from' => array('on_sale'), 'to' => 'draft'), //seller mgr
            'expire' => array('from' => array('on_sale'), 'to' => 'expired'),
            'pay_expire' => array('from' => array('paying'), 'to' => 'on_sale'),
            'pay_success' => array('from' => array('paying'), 'to' => 'payed'), //buyer
            'cancel_buy' => array('from' => array('paying'), 'to' => 'on_sale'), //buyer
            'event_start' => array('from' => array('payed'), 'to' => 'playing'),
            'cancel' => array('from' => array('payed'), 'to' => 'on_sale'), //mgr
            'event_end' => array('from' => array('playing'), 'to' => 'confirming'),
            'terminate' => array('from' => array('playing', 'confirming'), 'to' => 'terminated'), //mgr
            'confirm' => array('from' => array('confirming'), 'to' => 'finish'),
            'confirm_expire' => array('from' => array('confirming'), 'to' => 'finish'),
        ),
        'callbacks' => array(
            'before' => array(
                array( //待支付->已支付
                    'from' => 'paying',
                    'to' => 'payed',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //冻结会员相应金额
                            $instantOrderFinance = new InstantOrderFinance($instant);
                            $instantOrderFinance->buy();
                        }
                ),
                array( //打球中->终止
                    'from' => 'playing',
                    'to' => 'terminated',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //解冻会员金额
                            $instantOrderFinance = new InstantOrderFinance($instant);
                            $instantOrderFinance->terminate();
                        }
                ),
                array( //待确认->完成
                    'from' => 'confirming',
                    'to' => 'finish',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                            //场馆帐户充钱 收取会员冻结金额
                            $instantOrderFinance = new InstantOrderFinance($instant);
                            $instantOrderFinance->execute();
                        }
                ),
                array( //已支付->上架
                    'from' => 'payed',
                    'to' => 'on_sale',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //解冻会员金额
                            $instantOrderFinance = new InstantOrderFinance($instant);
                            $instantOrderFinance->cancel();

                            //清除买家标志
                            $instant->buyer = $instant->buyer_name = null;
                            $instant->save();

                            //给场馆发送取消通知
                            Notify::sendWithBusiness(NOTIFY_TYPE_HALL_INSTANT_ORDER_CANCELED, $instant->id);
                        }
                ),
            ),
            'after' => array(
                array( //草稿->待售（上架）
//                    'from' => 'draft',
                    'to' => 'on_sale',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动开始时间及卖家ID
                            $user = Auth::getUser();
                            if ($user instanceof User) {
                                empty($instant->seller) && $instant->seller = $user->user_id;
                                //记录expire_time=活动开始时间)
                                $expireTime = strtotime($instant->event_date) + (($instant->start_hour) * 3600);
                                $instant->expire_time = $expireTime;
                                $instant->save();
                            }
                        }
                ),
                array( //待售->待支付（购买）
                    'from' => 'on_sale',
                    'to' => 'paying',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time = update_at->支付过期时间--暂定两分钟
                            $user = Auth::getUser();
                            if ($user instanceof User) {
                                $instant->buyer = $user->user_id;
                                $instant->buyer_name = $user->nickname;
                                $expireTime = strtotime($instant->updated_at) + 600;
                                $instant->expire_time = $expireTime;
                                $instant->save();
                            }


                        }
                ),
                array( //待售->草稿（下架）
                    'from' => 'on_sale',
                    'to' => 'draft',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                        }
                ),
                array( //待售->过期（活动开始无法售卖）
                    'from' => 'on_sale',
                    'to' => 'expired',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                        }
                ),
                array( //待支付->待售（支付过期）
                    'from' => 'paying',
                    'to' => 'on_sale',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除买家信息
                            $instant->buyer = NULL;
                            $instant->buyer_name = NULL;;
                            $expireTime = strtotime($instant->event_date) + (($instant->start_hour) * 3600);
                            //记录expire_time=活动开始时间
                            $instant->expire_time = $expireTime;
                            $instant->save();
                        }
                ),
                array( //待支付->已支付
                    'from' => 'paying',
                    'to' => 'payed',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //短信场馆&会员
                            //记录expire_time=活动开始时间
                            $expireTime = strtotime($instant->event_date) + (($instant->start_hour) * 3600);
                            $instant->expire_time = $expireTime;
                            $instant->save();

                            Notify::sendWithBusiness(NOTIFY_TYPE_USER_INSTANT_ORDER_PAYED, $instant->id);

                            Notify::sendWithBusiness(NOTIFY_TYPE_HALL_INSTANT_ORDER_SOLD, $instant->id);
                        }
                ),
                array( //已支付->取消
                    'from' => 'payed',
                    'to' => 'canceled',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            // 删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                        }
                ),
                array( //已支付->打球中
                    'from' => 'payed',
                    'to' => 'playing',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动结束时间
                            $expireTime = strtotime($instant->event_date) + (($instant->end_hour) * 3600);
                            $instant->expire_time = $expireTime;
                            $instant->save();
                        }
                ),
                array( //打球中->待确认
                    'from' => 'playing',
                    'to' => 'confirming',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动结束时间+sometime
                            $expireTime = strtotime($instant->event_date) + (($instant->end_hour) * 3600 + 86400);
                            $instant->expire_time = $expireTime;
                            $instant->save();
                        }
                ),
                array( //打球中->终止
                    'from' => 'playing',
                    'to' => 'terminated',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {

                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                        }
                ),
                array( //待确认->终止
                    'from' => 'confirming',
                    'to' => 'terminated',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();
                        }
                ),
                array( //待确认->完成
                    'from' => 'confirming',
                    'to' => 'finish',
                    'do' => function (InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            $instant->expire_time = NULL;
                            $instant->save();

                        }
                ),
            )
        )
    ),

    'reserve_order' => array(
        'states' => array(
            RESERVE_STAT_INIT => array( //初始化 - 草稿 - 待处理
                'type' => Finite\State\StateInterface::TYPE_INITIAL,
                'properties' => array(),
            ),
            RESERVE_STAT_UNPAY => array( //待支付
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            RESERVE_STAT_PAYED => array( //已支付
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            RESERVE_STAT_SUBED => array( //已分账
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            RESERVE_STAT_CLOSED => array( //已结束
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            RESERVE_STAT_CANCELED => array( //已取消
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            RESERVE_STAT_FAILED => array( //预订失败
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            )
        ),
        'transitions' => array(
            'modify' => array('from' => array(RESERVE_STAT_INIT), 'to' => RESERVE_STAT_INIT),
            'pay_success' => array('from' => array(RESERVE_STAT_UNPAY), 'to' => RESERVE_STAT_PAYED), //buyer
            'book_success' => array('from' => array(RESERVE_STAT_INIT), 'to' => RESERVE_STAT_UNPAY),
            'book_fail' => array('from' => array(RESERVE_STAT_INIT), 'to' => RESERVE_STAT_FAILED),
            'cancel' => array('from' => array(RESERVE_STAT_PAYED), 'to' => RESERVE_STAT_CANCELED),
        ),
        'callbacks' => array(
            'before' => array(
                array( //待支付->已支付
                    'from' => RESERVE_STAT_UNPAY,
                    'to' => RESERVE_STAT_PAYED,
                    'do' => function (ReserveOrder $reserve, \Finite\Event\TransitionEvent $e) {
                            $reserveOrderFinance = new ReserveOrderFinance($reserve);
                            $reserveOrderFinance->buy();
                        }
                ),
                array( //已支付->取消
                    'from' => RESERVE_STAT_PAYED,
                    'to' => RESERVE_STAT_CANCELED,
                    'do' => function (ReserveOrder $reserve, \Finite\Event\TransitionEvent $e) {
                            $reserveOrderFinance = new ReserveOrderFinance($reserve);
                            $reserveOrderFinance->cancel();
                        }
                )
            ),
            'after' => array(
                array( //待支付->已支付
                    'from' => 'paying',
                    'to' => 'payed',
                    'do' => function (ReserveOrder $reserve, \Finite\Event\TransitionEvent $e) {
                            Log::debug('reserve order pay after');
                        }
                )
            )
        )
    ),

    'seeking' => array(
        'states' => array(
            SEEKING_STATE_CLOSED => array( //初始化 关门
                'type' => Finite\State\StateInterface::TYPE_INITIAL,
            ),
            SEEKING_STATE_OPENED => array( //开门
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_STATE_OPENED_EXPIRED => array( //开门已过期
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_STATE_FULLED => array( //满员
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_STATE_EXPIRED => array( //已过期
                'type' => Finite\State\StateInterface::TYPE_FINAL,
            ),
            SEEKING_STATE_FULL_CHECKING => array(//满员检测状态
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            )
        ),
        'transitions' => array(
            'modify' => array('from' => array(SEEKING_STATE_CLOSED), 'to' => SEEKING_STATE_CLOSED),
            'open' => array('from' => array(
                SEEKING_STATE_CLOSED, SEEKING_STATE_OPENED_EXPIRED), 'to' => SEEKING_STATE_OPENED),
            'close' => array('from' => array(SEEKING_STATE_OPENED), 'to' => SEEKING_STATE_CLOSED),

            'increase' => array('from' => array(
                SEEKING_STATE_OPENED, SEEKING_STATE_FULLED), 'to' => SEEKING_STATE_OPENED),
            'decrease' => array('from' => array(SEEKING_STATE_OPENED), 'to' => SEEKING_STATE_FULL_CHECKING),

            'auto_open' => array('from' => array(SEEKING_STATE_FULL_CHECKING), 'to' => SEEKING_STATE_OPENED),
            'auto_full' => array('from' => array(SEEKING_STATE_FULL_CHECKING), 'to' => SEEKING_STATE_FULLED),
            'refresh' => array('from' => array(SEEKING_STATE_FULL_CHECKING), 'to' => SEEKING_STATE_FULL_CHECKING),

            'expire' => array('from' => array(
                SEEKING_STATE_CLOSED, SEEKING_STATE_OPENED), 'to' => SEEKING_STATE_EXPIRED),

            'open_expire' => array('from' => array(SEEKING_STATE_OPENED), 'to' => SEEKING_STATE_OPENED_EXPIRED),
        ),
        'callbacks' => array(
            'before' => array(
                array( //
                    'to' => SEEKING_STATE_OPENED,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            if($seeking->sold >= $seeking->store){
                                throw new Exception('坑已满，不能开门！');
                            }
                        }
                ),
            ),
            'after' => array(
                array(
                    'to' => SEEKING_STATE_FULL_CHECKING,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            $fsm = new SeekingFsm($seeking);
                            $fsm->apply($seeking->sold == $seeking->store ? 'auto_full' : 'auto_open');
                        }
                ),
                array(
                    'to' => SEEKING_STATE_OPENED_EXPIRED,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            /**
                             * 场馆发送提醒短信
                             */
                        }
                )
            )
        )
    ),

    'seeking_order' => array(
        'states' => array(
            SEEKING_ORDER_STATE_DISPOSING => array( //处理中
                'type' => Finite\State\StateInterface::TYPE_INITIAL,
            ),
            SEEKING_ORDER_STATE_DISPOSE_EXPIRED => array( //过期未处理
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_ORDER_STATE_ACCEPTED => array( //已接受
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_ORDER_STATE_REJECTED => array( //已拒绝
                'type' => Finite\State\StateInterface::TYPE_NORMAL,
            ),
            SEEKING_ORDER_STATE_CANCELED => array( //已取消
                'type' => Finite\State\StateInterface::TYPE_FINAL,
            )
        ),
        'transitions' => array(
            'accept' => array('from' => array(SEEKING_ORDER_STATE_DISPOSING), 'to' => SEEKING_ORDER_STATE_ACCEPTED),
            'reject' => array('from' => array(SEEKING_ORDER_STATE_DISPOSING), 'to' => SEEKING_ORDER_STATE_REJECTED),
            'cancel' => array('from' => array(SEEKING_ORDER_STATE_ACCEPTED), 'to' => SEEKING_ORDER_STATE_CANCELED),
            'expire' => array('from' => array(SEEKING_ORDER_STATE_DISPOSING), 'to' => SEEKING_ORDER_STATE_DISPOSE_EXPIRED),
            'rejoin' => array('from' => array(SEEKING_ORDER_STATE_DISPOSE_EXPIRED), 'to' => SEEKING_ORDER_STATE_DISPOSING),
        ),
        'callbacks' => array(
            'before' => array(
                array( //
                    'to' => SEEKING_ORDER_STATE_DISPOSING,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            /**
                             * 给约球减-
                             */
                        }
                ),
            ),
            'after' => array(
                array(
                    'to' => SEEKING_ORDER_STATE_CANCELED,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            /**
                             * 给约球加-
                             */
                        }
                ),
                array(
                    'to' => SEEKING_ORDER_STATE_DISPOSE_EXPIRED,
                    'do' => function (Seeking $seeking, \Finite\Event\TransitionEvent $e) {
                            /**
                             * 提醒用户已过期
                             */
                        }
                )
            )
        )
    )
);
