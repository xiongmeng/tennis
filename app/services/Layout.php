<?php
class Layout{

    //高亮菜单项
    public static $highlightHeader = '';
    public static function setHighlightHeader($headerKey){
        self::$highlightHeader = $headerKey;

        $headers = Config::get('acl.headers');

        self::$breadcrumb = array();
        foreach($headers as $headerId => $header){
            if($headerKey == $headerId){
                self::$breadcrumb[] = $header;
            }else if(isset($header['children']) && array_key_exists($headerKey, $header['children'])){
                self::$breadcrumb[] = $header;
                self::$breadcrumb[] = $header['children'][$headerKey];
            }
        }
    }

    public static $breadcrumb = array();
    public static function appendBreadCrumbs(array $breadCrumbs){

    }
}