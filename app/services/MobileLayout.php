<?php
class MobileLayout{
    public static $title = '网球通';

    public static $previousUrl = null;

    public static $activeService = null;
    public static $setUrl = null;
    public static $services = array(
//        'seeking' => array(
//            'url' => '/seeking/list',
//            'label' => '约球畅打'
//        ),
        'reserve' => array(
            'url' => '/hall/wx/list',
            'label' => '预约订场'
        ),
        'instant' => array(
            'url' => '/mobile_home/instant',
            'label' => '即时订场'
        ),
        'center' => array(
            'url' => '/mobile_buyer',
            'label' => '个人中心'
        )
    );
}