<?php

return array(
    'instant_order' => array(
        'class'       => 'Document',
        'states'      => array(
            'draft'    => array(//草稿
                'type'       => Finite\State\StateInterface::TYPE_INITIAL,
                'properties' => array('deletable' => true, 'editable' => true),
            ),
            'on_sale' => array(//待售
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'paying' => array(//等待支付
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'payed' => array(//支付成功
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'playing' => array(//打球中
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'confirming' => array(//等待确认
                'type'       => Finite\State\StateInterface::TYPE_NORMAL,
                'properties' => array(),
            ),
            'canceled' => array(//已取消
                'type'       => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'expired' => array(//过期未售出
                'type'       => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'terminated' => array(//已终止
                'type'       => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array(),
            ),
            'finish' => array(//完成
                'type'       => Finite\State\StateInterface::TYPE_FINAL,
                'properties' => array('printable' => true),
            )
        ),
        'transitions' => array(
            'online' => array('from' => array('draft'), 'to' => 'on_sale'),//seller
            'buy' => array('from' => array('on_sale'), 'to' => 'paying'),//buyer
            'offline' => array('from' => array('on_sale'), 'to' => 'draft'),//seller mgr
            'expire' => array('from' => array('on_sale'), 'to' => 'expired'),
            'pay_expire' => array('from' => array('paying'), 'to' => 'on_sale'),
            'pay_success' => array('from' => array('paying'), 'to' => 'payed'),//buyer
            'event_start' => array('from' => array('payed'), 'to' => 'playing'),
            'cancel' => array('from' => array('payed'), 'to' => 'canceled'),//mgr
            'event_end' => array('from' => array('playing'), 'to' => 'confirming'),
            'terminate' => array('from' => array('playing','confirming'), 'to' => 'terminated'),//mgr
            'confirm' => array('from' => array('confirming'), 'to' => 'finish'),
            'confirm_expire'=>  array('from' => array('confirming'), 'to' => 'finish'),
        ),
        'callbacks' => array(
            'before' => array(
                array(//草稿->上架
                    'from' => 'on_sale',
                    'to' => 'paying',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time = update_at

                        }
                ),
            ),
            'after' => array(
                array(//草稿->待售（上架）
                    'from'=>'draft',
                    'to' => 'on_sale',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动开始时间及卖家ID
                            $expireTime = strtotime($instant->event_date)+(($instant->start_hour)*3600);
                            if(Auth::check()){
                                $user = Auth::getUser();
                                $userID =$user['user_id'];


                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime,'seller'=>$userID));
                            //记录expire_time=活动开始时间)
                            }
                        }
                ),
                array(//待售->待支付（购买）
                    'from' => 'on_sale',
                    'to' => 'paying',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time = update_at->支付过期时间--暂定两分钟
                            $expireTime = strtotime($instant->updated_at)+120;
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime));
                            if(Auth::check()){
                                $user = Auth::getUser();
                                $userID =$user['user_id'];
                                $userName = $user['nickname'];
                            InstantOrder::where('id','=',$instant->id)->update(array('buyer'=>$userID,'buyer_name'=>$userName));
                            }
                        }
                ),
                array(//待售->草稿（下架）
                    'from' => 'on_sale',
                    'to' => 'draft',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                ),
                array(//待售->过期（活动开始无法售卖）
                    'from' => 'on_sale',
                    'to' => 'expired',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                ),
                array(//待支付->待售（支付过期）
                    'from' => 'paying',
                    'to' => 'on_sale',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                           //记录expire_time=活动开始时间
                            $expireTime = strtotime($instant->event_date)+(($instant->start_hour)*3600);
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime));
                            //删除买家信息
                            InstantOrder::where('id','=',$instant->id)->update(array('buyer'=>NULL,'buyer_name'=>NULL));
                        }
                ),
                array(//待支付->已支付
                    'from' => 'paying',
                    'to' => 'payed',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //短信场馆&会员
                            //冻结会员相应金额
                            //记录expire_time=活动开始时间
                            $expireTime = strtotime($instant->event_date)+(($instant->start_hour)*3600);
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime));
                        }
                ),
                array(//已支付->取消
                    'from' => 'payed',
                    'to' => 'canceled',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            // 删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                ),
                array(//已支付->打球中
                    'from' => 'payed',
                    'to' => 'playing',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动结束时间
                            $expireTime = strtotime($instant->event_date)+(($instant->end_hour)*3600);
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime));
                        }
                ),
                array(//打球中->待确认
                    'from' => 'playing',
                    'to' => 'confirming',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //记录expire_time=活动结束时间+sometime
                            $expireTime = strtotime($instant->event_date)+(($instant->end_hour)*3600+86400);
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>$expireTime));
                        }
                ),
                array(//打球中->终止
                    'from' => 'playing',
                    'to' => 'terminated',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //解冻会员金额
                            //删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                ),
                array(//待确认->终止
                    'from' => 'draft',
                    'to' => 'terminated',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                            //删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                ),
                array(//待确认->完成
                    'from' => 'confirming',
                    'to' => 'finish',
                    'do' => function(InstantOrder $instant, \Finite\Event\TransitionEvent $e) {
                              //场馆帐户充钱 收取会员冻结金额
                            //删除过期时间
                            InstantOrder::where('id','=',$instant->id)->update(array('expire_time'=>Null));
                        }
                )
            )
        )
    )
);
