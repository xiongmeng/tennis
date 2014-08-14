<?php

/*
 * @package     xFramework
 * @copyright   xFramework
 * @author      eks
 * @version     $create by eks 2009-10-09 $
 */
require_once('alipay_function.php');

class Alipay{

    static private $oInstance__;

    static public function &instance(){
        if(!isset(self::$oInstance__)){
            $sClass = __CLASS__;
            self::$oInstance__ = new $sClass;
        }
        return self::$oInstance__;
    }

    /*
     * 支付
     * @param   int     $iMoney         金额
     * @param   string  $sOrderNo       订单号
     * @param   string  $sPayMethod     支付类型
     * @param   string  $sDefaultBank   所用银行
     * @param   string  $sSubject       订单名称
     * @param   string  $sBody          订单描述
     * @param   string  $sShowUrl       产品链接
     * @return  string
     */

    public static function Payment($iMoney,$sOrderNo,$sPayMethod,$sDefaultBank,$sSubject,$sBody=null,$sShowUrl=null){
        if(empty($iMoney))
            return false;
        $aPayment['iMoney'] = $iMoney;
        $aPayment['sPayMethod'] = $sPayMethod;
        $aPayment['sDefaultBank'] = $sDefaultBank;

        $aPayment['sSubject'] = !empty($sSubject) ? ($sSubject) : '充值';//订单名称
        $aPayment['sBody'] = !empty($sBody) ? $sBody : ('充值' . $iMoney . '元');//订单描述
        $aPayment['sTradeNo'] = $sOrderNo;
        //防钓鱼
        $aPayment['sAntiPhishingKey'] = '';//防钓鱼时间戳
        $aPayment['sExterInvokeIP'] = '';//
        //扩展功能参数
        //自定义参
        $aPayment['sBuyerEmail'] = '';//默认买家支付宝账号
        $aPayment['sExtraCommonParam'] = '';//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
        //分润
        $aPayment['sRoyaltyType'] = '';//提成类型，该值为固定值：10，不需要修改
        $aPayment['sRoyaltyParameters'] = '';//

        $aAlipay = Config::get('alipay.aAlipay');
        $aParameter = array(
            'service'=>'create_direct_pay_by_user',//接口名称，不需要修改
            'payment_type'=>'1',//交易类型，不需要修改
            /* 获取配置文件(alipay_config.php)中的 */
            'partner'=>$_ENV['Alipay_PARNTER'],
            'seller_email'=>$_ENV['Alipay_SELLER_EMAIL'],
            'return_url'=>$aAlipay['return_url'],
            'notify_url'=>$aAlipay['notify_url'],
            '_input_charset'=>$aAlipay['input_charset'],
            'show_url'=>(!empty($sShowUrl)?$sShowUrl:$aAlipay['show_url']),
            'out_trade_no'=>$aPayment['sTradeNo'],//从订单数据中动态获取到的必填参
            'subject'=>$aPayment['sSubject'],
            'body'=>$aPayment['sBody'],
            'total_fee'=>$aPayment['iMoney'],
            'paymethod'=>$aPayment['sPayMethod'],//扩展功能参数——网银提前
            'defaultbank'=>$aPayment['sDefaultBank'],
            'anti_phishing_key'=>$aPayment['sAntiPhishingKey'],//扩展功能参数——防钓鱼
            'exter_invoke_ip'=>$aPayment['sExterInvokeIP'],
            'buyer_email'=>$aPayment['sBuyerEmail'],//扩展功能参数——自定义参
            'extra_common_param'=>$aPayment['sExtraCommonParam'],
            'royalty_type'=>$aPayment['sRoyaltyType'],//扩展功能参数——分润
            'royalty_parameters'=>$aPayment['sRoyaltyParameters']
        );
        require_once('AlipayService.php');
        //构造即时到帐接口
        $alipayService = new AlipayService($aAlipay);
        $html_text = $alipayService->create_direct_pay_by_user($aParameter);
        return $html_text;
//        $oAlipay = new alipay_service($aParameter,$aAlipay['key'],$aAlipay['sign_type']);
//        $sHtmlText = $oAlipay->build_form();
//        return $sHtmlText;
    }

    /*
     * 验证结果-交易过程中服务器通知的页面
     * @param   int     $iMoney         金额
     * @param   string  $sOrderNo       订单号
     * @param   string  $sPayNo         支付类型
     * @param   string  $sTradeStatus
     * @return  bool
     */

    public function notifyVerify($eCBType,$iID,$iMoney,$sPayNo,$sBuyer){
        $aAlipay = Config::get('alipay.aAlipay');
        require_once('AlipayNotify.php');

        $oAlipay = new AlipayNotify($aAlipay);//构造通知函数信息
        $sVerifyResult = $oAlipay->verifyNotify();

        if($sVerifyResult){//验证成功
            $bStatus = true;
        }
        else{
            $bStatus = false;
        }

        $aParams = array("status"=>$bStatus,"iToken"=>$iID,"buyer"=>$sBuyer,"money"=>$iMoney,"trade_no"=>$sPayNo);
        //"status"=>$bStatus,"iToken"=>$iID,"money"=>$iMoney,"trade_no"=>$aReturn['trade_no']
        if($eCBType==0x1002){//支付宝的服务器通知页面
            return doApiNotifyAndReturn($eCBType, $aParams);

        }else if($eCBType==0x1003){//支付宝的服务器返回页面
            return doApiNotifyAndReturn($eCBType, $aParams);
        }
    }

    /*
     * 付完款后验证
     * @return  string
     */

    public function returnVerify($eCBType,$iID,$iMoney,$sPayNo,$sBuyer){
        $aAlipay = Config::get('alipay.aAlipay');
        require_once('AlipayNotify.php');

        $oAlipay = new AlipayNotify($aAlipay);
        unset($_REQUEST['XDEBUG_SESSION_START']);
        $sVerifyResult = $oAlipay->verifyReturn();//计算得出通知验证结
        if($sVerifyResult){//验证
            $bStatus = true;
        }else{
            //验证失败
            //如要调试，请看alipay_notify.php页面的return_verify函数，比对sign和mysign的值是否相等，或者检查$veryfy_result有没有返回true
            $bStatus = false;
        }
        $aParams = array("status"=>$bStatus,"iToken"=>$iID,"buyer"=>$sBuyer,"money"=>$iMoney,"trade_no"=>$sPayNo);
        //"status"=>$bStatus,"iToken"=>$iID,"money"=>$iMoney,"trade_no"=>$aReturn['trade_no']
        if($eCBType==0x1002){//支付宝的服务器通知页面
            return doApiNotifyAndReturn($eCBType, $aParams);

        }else if($eCBType==0x1003){//支付宝的服务器返回页面
            return doApiNotifyAndReturn($eCBType, $aParams);
        }

    }

}

?>