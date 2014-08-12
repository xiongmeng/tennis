<?php

return array(
    'headers' => array(
        '2' => array(
            'label' => '即时订场',
            'name' => 'instant',
            'url' => '/instant_order_on_sale',
            'children' => array()
        ),
        '4' => array(
            'label' => '我的订单',
            'name' => 'mgr_court',
            'url' => '/instant_order_buyer',
            'children' => array()
            ),
        '5' => array(
            'label' => '账户明细',
            'name' => 'mgr_court',
            'url' => '/billing_buyer/account_balance',
            'children' => array()
        ),
        '6' => array(
            'label' => '流水明细',
            'name' => 'mgr_court',
            'url' => '/billing_mgr/account_balance',
            'children' => array()
        ),
        '8' => array(
            'label' => '订单管理',
            'name' => 'instant_order_mgr',
            'url' => '/instant_order_mgr',
            'children' => array()

        ),
        '9' => array(
            'label' => '场地管理',
            'name' => 'mgr_court',
            'url' => '/order_court_manage',
            'children' => array()
        ),
        '11' => array(
            'label' => '已售场地',
            'name' => 'instant_order_seller',
            'url' => '/instant_order_seller',
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
            '8','6'
        ),
        '3' => array(
             '11', '9'
        )
    )
);
