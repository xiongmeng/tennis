<?php
Route::get('/recharge/alipay/{money?}/{actionType?}/{actionToken}',
    array('before' => 'auth', function ($money, $actionType, $actionToken) {
        $user = Auth::getUser();

        //添加一条充值记录
        $aRecharge = new Recharge();
        $aRecharge->user_id = $user->user_id;
        $aRecharge->money = $money;
        $aRecharge->type = 1; //支付方式
        $aRecharge->stat = 1; //初始化
        $aRecharge->createtime = time();
        $aRecharge->callback_action_token = $actionToken;
        $aRecharge->callback_action_type = $actionType; //购买即时订单
        $aRecharge->save();
        $iRechargeID = $aRecharge->id;

        if (!is_numeric($money) || $money < 0) {
            return sprintf('充值额度必须为大于零的数字（%s）', $money);
        }

        $isDeBug = Config::get('app.debug');
        $isDeBug && $money = 0.01;

        //执行支付宝支付
        $sHtmlText = Alipay::Payment($money, sprintf("%08d", $iRechargeID), null, null, "付款", "付款");
        return $sHtmlText;
    }));

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

        if($recharge->callback_action_type == RECHARGE_CALLBACK_PAY_INSTANT_ORDER){
            $instantOrderString = $recharge->callback_action_token;
            $instantOrderIds = explode(',', $instantOrderString);

            DB::beginTransaction();
            try{
                $manager = new InstantOrderManager();
                $result = $manager->batchPay($instantOrderIds, $recharge->user_id);
                DB::commit();

                if($result['status'] == 'pay_success'){
                    return '支付成功';
                }else{
                    return '支付失败';
                }
            }catch (Exception $e){
                DB::rollBack();
                throw $e;
            }
        }
        return '充值成功';
    } else {
        return 'fail';
    }
});