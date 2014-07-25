<?php

return array(
    '1' => array(
        'label' => '首页',
        'name' => 'index',
        'url' => '/home',
        'children' => array(),
    ),
    '2' => array(
        'label' => '即时订场',
        'name' => 'instant',
        'url' => '/instant',
        'children' => array()
    ),
    '3' => array(
        'label' => '发布场地',
        'name' => 'instant_push',
        'url' =>'instant_push',
        'children' => array()
    ),
    '4' => array(
        'label' => '个人中心',
        'name' => 'center',
        'url' => '/center',
        'children' => array(
            '40' => array(
                '401' => array(
                'label' => '用户管理',
                'name' => 'mgr_user',
                'url' =>'mgr_user',
                'children' => array()

                ),
                '402' => array(
                    'label' => '场馆管理',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array(
                        '4021' => array(
                            'label' => '全部场馆',
                            'name' => 'mgr_court_list',
                            'url' => 'mgr_court_list'
                        ),
                        '4022' => array(
                            'label' => '即时场馆',
                            'name' => 'mgr_court_instant',
                            'url' => 'mgr_court_instant'
                        ),
                        '4023' => array(
                            'label' => '场馆账户',
                            'name' => 'mgr_court_wallet',
                            'url' => 'mgr_court_wallet'
                        ),

                    )
                ),
                '403' => array(
                    'label' => '场地管理',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array(
                        '4031' => array(
                            'label' => '待售场地',
                            'name' => 'mgr_court_sale',
                            'url' => 'mgr_court_sale'
                        ),
                        '4032' => array(
                            'label' => '已售场地',
                            'name' => 'mgr_court_sold',
                            'url' => 'mgr_court_sold'
                        ),
                        '4033' => array(
                            'label' => '过期场地',
                            'name' => 'mgr_court_unsold',
                            'url' => 'mgr_court_unsold'
                        )

                    )
                ),
                '404' => array(
                    'label' => '订单管理',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array(
                        '4041' => array(
                           'label' => '即时订单',
                            'name' => 'mgr_instant_list',
                            'url' => 'mgr_instant_list'
                        )
                    )
                )
            ),
            '41' => array(
                '411' => array(
                    'label' => '我的订单',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array()
                ),
                '412' => array(
                    'label' => '账户明细',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array()
                )
            ),
            '42' => array(
                '421' => array(
                    'label' => '场地管理',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array()
                ),
                '422' => array(
                    'label' => '账户详情',
                    'name' => 'mgr_court',
                    'url' =>'mgr_court',
                    'children' => array()
                ),
            )
        )
    )

);
