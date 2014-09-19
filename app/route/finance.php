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
    $aParams = $aError = array();
    $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
    $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
    $sPayNo = Input::get('trade_no'); //支付宝交易号
    $sTradeStatus = Input::get('trade_status'); //交易状态
    $sBuyer = Input::get('buyer_email');
    $notify = new Alipay;
    $isDeBug = Config::get('app.debug');

    $bBes = $notify->notifyVerify(0x1003, intval($sTradeNo), $isDeBug ? 1000 : $iMoney, $sPayNo, $sBuyer);
    if ($bBes) {
        return 'success';
    } else {
        return 'fail';
    }
});

Route::get('/alipay_return', function () {
    $aParams = $aError = array();
    $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
    $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
    $sPayNo = Input::get('trade_no'); //支付宝交易号
    $sTradeStatus = Input::get('trade_status'); //交易状态
    $sBuyer = Input::get('buyer_email');
    $notify = new Alipay;

    $isDeBug = Config::get('app.debug');

    $bBes = $notify->returnVerify(0x1003, intval($sTradeNo), $isDeBug ? 1000 : $iMoney, $sPayNo, $sBuyer);
    if ($bBes) {
        $rechargeId = intval($sTradeNo);
        $recharge = Recharge::findOrFail($rechargeId);

        if ($recharge->callback_action_type == RECHARGE_CALLBACK_PAY_INSTANT_ORDER) {
            $instantOrderString = $recharge->callback_action_token;
            $instantOrderIds = explode(',', $instantOrderString);
            $appUserID = $recharge->app_user_id;
            $appID = $recharge->app_id;

            DB::beginTransaction();
            try {
                $manager = new InstantOrderManager();
                $result = $manager->batchPay($instantOrderIds, $recharge->user_id);
                DB::commit();
                if ($result['status'] == 'pay_success') {
                    if ($appID == 2 && $appUserID) {
                        return Redirect::to('/pay_success?app_id=' . $appID . '&app_user_id=' . $appUserID);

                    }

                    return '支付成功';
                } else {
                    return '支付失败';
                }
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
        return '充值成功';
    } else {
        return 'fail';
    }
});

Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT']), function () {
    Route::get('/recharge/wechatpay', function () {

        //使用jsapi接口
        $jsApi = new JsApi();

        //=========步骤1：网页授权获取用户openid============
        //通过code获得openid
        if (!isset($_GET['code']))
        {
            $rechargeId = Input::get('recharge_id');
            //触发微信返回code码
            $url = $jsApi->createOauthUrlForCode(URL::current(), $rechargeId);
            Log::debug($url);
            Header("Location: $url");exit;
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
        $unifiedOrder->setParameter("body","贡献一分钱");//商品描述
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
    Route::get('/wechatpay_notify', function () {

    });
});