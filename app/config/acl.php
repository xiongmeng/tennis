<?php

return array(
    'headers' => array(
        '2' => array(
            'label' => '即时订场',
            'url' => '/hall_on_sale',
            'children' => array()
        ),
        '4' => array(
            'label' => '我的订单',
            'url' => '/instant_order_buyer',
            'children' => array()
            ),
        '5' => array(
            'label' => '账户明细',
            'url' => '/billing_buyer/account_balance',
            'children' => array()
        ),
        '6' => array(
            'label' => '流水',
            'url' => '/billing_mgr/account_balance',
            'children' => array()
        ),
        '8' => array(
            'label' => '即时订单',
            'url' => '/instant_order_mgr/all',
            'children' => array()
        ),
        '9' => array(
            'label' => '场地管理',
            'url' => '/order_court_manage',
            'children' => array()
        ),
        '11' => array(
            'label' => '已售场地',
            'url' => '/instant_order_seller',
            'children' => array()

        ),
        '12' => array(
            'label' => '预订信息短信通知手机号码设定',
            'url' => '/set_receive_sms_telephone',
            'children' => array()

        ),
        '13' => array(
            'label' => '预约订单',
            'url' => '/reserve_order_mgr/book_pending',
            'children' => array()
        ),
        '14' => array(
            'label' => '通知',
            'url' => '/notify/record',
            'children' => array()
        ),
        '15' => array(
            'label' => '用户',
            'url' => '/user',
            'children' => array()
        ),
        '16' => array(
            'label' => '账户',
            'url' => '/account',
            'children' => array()
        ),
        '17' => array(
            'label' => '微信用户',
            'url' => '/app',
            'children' => array()
        ),
    ),

    'roles' => array(
        '1' => array(
            'label' => '会员',
            'name' => 'user'
        ),
        '2' => array(
            'label' => '管理员',
            'name' => 'mgr'
        ),
        '3' => array(
            'label' => '场馆',
            'name' => 'hall'
        )
    ),

    'roles_headers' => array(
        '1' => array(
             '2', '4', '5'
        ),
        '2' => array(
            '8','6','13','14','15','16','17'
        ),
        '3' => array(
              '11', '9', '12'
        )
    )
);
