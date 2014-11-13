<?php

return array(
    'headers' => array(
        'nav_即时订场（用户侧）' => array(
            'label' => '即时订场',
            'url' => '/hall_on_sale',
        ),
        'nav_我的订单' => array(
            'label' => '我的订单',
            'url' => '/instant_order_buyer',
            ),
        'nav_账户明细（用户侧）' => array(
            'label' => '账户明细',
            'url' => '/billing_buyer/account_balance',
        ),
        'nav_即时订单列表（管理员侧）' => array(
            'label' => '即时订单',
            'url' => '/instant_order_mgr/payed',
        ),
        'nav_场地管理（场馆侧）' => array(
            'label' => '场地管理',
            'url' => '/order_court_manage',
        ),
        'nav_已售场地列表（场馆侧）' => array(
            'label' => '已售场地',
            'url' => '/instant_order_seller',
        ),
        'nav_预订信息短信通知手机号码设定' => array(
            'label' => '预订信息短信通知手机号码设定',
            'url' => '/set_receive_sms_telephone',
        ),
        'nav_预约订单一级列表' => array(
            'label' => '预约订单',
            'url' => '/reserve_order_mgr/book_pending',
            'children' => array(
                'nav_新增预约订单（管理员）' => array(
                    'label' => '新增预约订单',
                    'url' => '/reserve/create'
                ),
                'nav_预约订单列表（管理员）' => array(
                    'label' => '预约订单列表',
                    'url' => '/reserve_order_mgr/book_pending'
                ),
            )
        ),
        'nav_通知列表' => array(
            'label' => '通知',
            'url' => '/notify/record',
        ),
        'nav_用户一级菜单' => array(
            'label' => '用户管理',
            'url' => '#',
            'children' => array(
                'nav_用户列表' => array(
                    'label' => '用户列表',
                    'url' => '/user',
                ),
                'nav_账户列表' => array(
                    'label' => '账户列表',
                    'url' => '/account',
                ),
                'nav_微信用户列表' => array(
                    'label' => '微信用户列表',
                    'url' => '/app',
                ),
            )
        ),
        'nav_场馆一级菜单' => array(
            'label' => '场馆管理',
            'url' => '#',
            'children' => array(
                'nav_场馆列表（管理员）' => array(
                    'label' => '场馆列表',
                    'url' => '/hall/list/published'
                ),
                'nav_新增场馆' => array(
                    'label' => '新增场馆',
                    'url' => '/hall/create'
                ),
                'nav_法定节假日' => array(
                    'label' => '法定节假日',
                    'url' => '/holiday'
                ),
                'nav_已登记场馆' => array(
                    'label' => '已登记场馆',
                    'url' => '/hall/register/list'
                )
            )
        ),
        'nav_财务一级菜单' => array(
            'label' => '财务',
            'url' => '#',
            'children' => array(
                'nav_补款' => array(
                    'label' => '补款',
                    'url' => '/finance/recharge'
                ),
                'nav_扣款' => array(
                    'label' => '扣款',
                    'url' => '/finance/consume'
                ),
                'nav_流水列表（管理员侧）' => array(
                    'label' => '流水列表',
                    'url' => '/billing_mgr/account_balance',
                ),
            )
        ),
    ),

    'roles' => array(
        ROLE_USER => array(
            'label' => '会员',
            'name' => 'user'
        ),
        ROLE_MGR => array(
            'label' => '管理员',
            'name' => 'mgr'
        ),
        ROLE_HALL => array(
            'label' => '场馆',
            'name' => 'hall'
        )
    ),

    'roles_headers' => array(
        ROLE_USER => array(
             'nav_即时订场（用户侧）', 'nav_我的订单', 'nav_账户明细（用户侧）'
        ),
        ROLE_MGR => array(
            'nav_即时订单列表（管理员侧）',
            'nav_流水列表（管理员侧）',
            'nav_预约订单一级列表',
            'nav_新增预约订单（管理员）',
            'nav_预约订单列表（管理员）',
            'nav_通知列表',
            'nav_用户列表',
            'nav_用户一级菜单',
            'nav_账户列表',
            'nav_微信用户列表',
            'nav_场馆一级菜单',
            'nav_场馆列表（管理员）',
            'nav_新增场馆',
            'nav_法定节假日',
            'nav_已登记场馆',
            'nav_财务一级菜单',
            'nav_补款',
            'nav_扣款',
        ),
        ROLE_HALL => array(
            'nav_已售场地列表（场馆侧）',
            'nav_场地管理（场馆侧）',
            'nav_预订信息短信通知手机号码设定'
        )
    )
);
