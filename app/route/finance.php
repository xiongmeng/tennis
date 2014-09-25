<?php
Route::get('/recharge/alipay', function () {
    $rechargeId = Input::get('recharge_id');
    $recharge = Recharge::findOrFail($rechargeId);

    $appID = Input::get('app_id');
    $appUserID = Input::get('app_user_id');
    if ($appID && $appUserID) {
        $recharge->app_user_id = $appUserID;
        $recharge->app_id = $appID;

    }
    $recharge->type = PAY_TYPE_ALI; //支付方式
    $recharge->save();

    $money = $recharge->money;
    $isDeBug = Config::get('app.debug');
    $isDeBug && $money = 0.01;

    //执行支付宝支付
    $sHtmlText = Alipay::Payment($money, sprintf("%08d", $rechargeId), null, null, "付款", "付款");
    return $sHtmlText;
});

Route::get('/alipay_notify', function () {
    $oAlipay = new AlipayNotify(Config::get('alipay.aAlipay'));//构造通知函数信息
    $bBes = $oAlipay->verifyNotify();

    if ($bBes) {
        $userFinanceService = new UserFinance();
        $userFinanceService->doPaySuccess(Input::get('out_trade_no'), Input::get('buyer_email'), Input::get('total_fee'));

        return 'success';
    } else {
        return 'fail';
    }
});

Route::get('/alipay_return', function () {
    $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
    $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
    $sBuyer = Input::get('buyer_email');

    $oAlipay = new AlipayNotify(Config::get('alipay.aAlipay'));
    $bBes = $oAlipay->verifyReturn();//计算得出通知验证结

    if ($bBes) {
        $rechargeId = intval($sTradeNo);
        $recharge = Recharge::findOrFail($rechargeId);

        $userFinanceService = new UserFinance();
        $userFinanceService->doPaySuccess($recharge, $sBuyer, $iMoney);

        if ($recharge->app_id == APP_WE_CHAT) {
            return Redirect::to('/pay_success');
        }
        return '充值成功';
    } else {
        return 'fail';
    }
});

Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT']), function () {
    Route::get('/recharge/wechatpay', function () {

        //使用jsapi接口
        $jsApi = new WeChatJsApi();

        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code']))
        {
            $rechargeId = Input::get('recharge_id');
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(URL::current(), $rechargeId);
            Log::debug($url);
            return Redirect::to($url);
        }else
        {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $jsApi->setCode($code);
            $openId = $jsApi->getOpenId();
        }

        $rechargeId = Input::get('state');
        $recharge = Recharge::findOrFail($rechargeId);

        $recharge->type = PAY_TYPE_WE_CHAT; //支付方式
        $recharge->save();

        $money = $recharge->money;
        $isDeBug = Config::get('app.debug');
        $isDeBug && $money = 0.01;

        //=========步骤2：使用统一支付接口，获取prepay_id============
        //使用统一支付接口
        $unifiedOrder = new UnifiedOrder();

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $unifiedOrder->setParameter("openid","$openId");//商品描述
        $unifiedOrder->setParameter("body","网球儿");//商品描述
        //自定义订单号，此处仅作举例
        $unifiedOrder->setParameter("out_trade_no", $rechargeId);//商户订单号
        $unifiedOrder->setParameter("total_fee", intval($money * 100));//总金额
        $unifiedOrder->setParameter("notify_url", WxPayConf::NOTIFY_URL);//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        $unifiedOrder->setParameter("attach","终于尼玛调通了");//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID

        $prepay_id = $unifiedOrder->getPrepayId();
        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();
        Log::debug($jsApiParameters);

        return View::make('mobile.wechat_pay', array('jsApiParameters' => $jsApiParameters));
    });
});


Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT']), function () {
    Route::any('/wechatpay_notify', function () {
        //使用通用通知接口
        $notify = new WeChatNotify();

        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }


        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======

        //以log文件形式记录回调信息
        Log::info('weChatPay-【接收到的notify通知】', array('xml' =>$xml));

        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                Log::info('weChatPay-【通信出错】', array('xml' =>$xml));
            }
            elseif($notify->data["result_code"] == "FAIL"){
                Log::info('weChatPay-【业务出错】', array('xml' =>$xml));
            }
            else{
                Log::info('weChatPay-【支付成功】', array('xml' =>$xml));
            }

            $rechargeId = intval($notify->data['out_trade_no']);

            $userFinanceService = new UserFinance();
            $userFinanceService->doPaySuccess($rechargeId, $notify->data['openid'], $notify->data['total_fee']);

        }

        $returnXml = $notify->returnXml();
        echo $returnXml;
    });
});