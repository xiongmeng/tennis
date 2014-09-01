<?php
class MobileLayout{
    public static $title = '网球通';

    public static $previousUrl = null;

    public static $activeService = null;

    public static $services = array(
        'instant' => array(
            'url' => '/mobile_home/instant',
            'label' => '即时订场'
        ),
        'reserve' => array(
            'url' => '/mobile_home/reserve/recommend',
            'label' => '预约订场'
        ),
        'center' => array(
            'url' => '/mobile_buyer',
            'label' => '个人中心'
        )
    );
}