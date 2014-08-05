<?php

return array(
    'headers' => array(
        '1' => array(
            'label' => '首页',
            'name' => 'index',
            'url' => 'login',
            'children' => array(),
        ),
        '2' => array(
            'label' => '即时订场',
            'name' => 'instant',
            'url' => 'instant_order_on_sale',
            'children' => array()
        ),
        '3' => array(
            'label' => '发布场地',
            'name' => 'instant_push',
            'url' => 'instant_push',
            'children' => array()
        ),
        '4' => array(
            'label' => '个人中心',
            'name' => 'center',
            'url' => '',
            'children' => array(
                '401' => array(
                    'label' => '我的订单',
                    'name' => 'mgr_court',
                    'url' => 'instant_order_buyer',
                    'children' => array()
                ),
                '402' => array(
                    'label' => '账户明细',
                    'name' => 'mgr_court',
                    'url' => 'billing_buyer',
                    'children' => array()
                )
            )),
        '5' => array(
            'label' => '用户管理',
            'name' => 'mgr_user',
            'url' => '',
            'children' => array()

        ),
        '6' => array(
            'label' => '场馆管理',
            'name' => 'mgr_court',
            'url' => '',
            'children' => array(
                '601' => array(
                    'label' => '全部场馆',
                    'name' => 'mgr_court_list',
                    'url' => ''
                ),
                '602' => array(
                    'label' => '即时场馆',
                    'name' => 'mgr_court_instant',
                    'url' => 'mgr_court_instant'
                ),
                '603' => array(
                    'label' => '场馆账户',
                    'name' => 'mgr_court_wallet',
                    'url' => 'mgr_court_wallet'
                ),
            )
        ),
        '7' => array(
            'label' => '场地管理',
            'name' => 'mgr_court',
            'url' => '',
            'children' => array(
                '701' => array(
                    'label' => '待售场地',
                    'name' => 'mgr_court_sale',
                    'url' => ''
                ),
                '702' => array(
                    'label' => '已售场地',
                    'name' => 'mgr_court_sold',
                    'url' => ''
                ),
                '703' => array(
                    'label' => '过期场地',
                    'name' => 'mgr_court_unsold',
                    'url' => ''
                )
            )
        ),
        '8' => array(
            'label' => '订单管理',
            'name' => 'instant_order_seller',
            'url' => 'instant_order_seller',
            'children' => array()

        ),
        '9' => array(
            'label' => '场地管理',
            'name' => 'mgr_court',
            'url' => 'order_court_manage?hall_id=&court_id=',
            'children' => array()
        ),
        '10' => array(
            'label' => '账户详情',
            'name' => 'mgr_court',
            'url' => 'billing_seller',
            'children' => array()
        )
    ),

    'roles' => array(
        '1' => array(
            'label' => '会员'
        ),
        '2' => array(
            'label' => '管理员'
        ),
        '3' => array(
            'label' => '场馆'
        )
    ),

    'roles_headers' => array(
        '1' => array(
             '2', '4'
        ),
        '2' => array(
            '4', '5', '6', '7', '8'
        ),
        '3' => array(
            '8', '9', '10'
        )
    )
);
