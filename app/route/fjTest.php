<?php

Route::group(array('prefix' => 'fj'), function () {

    Route::get('/', function () {
        return 'I am fj';
    });

    Route::get('/userid', function () {
        if (Auth::check()) {
            $data = Auth::getUser();
            $user_id = $data['user_id'];
            $role_id = DB::select('select `role_id` from `gt_relation_user_role` where `user_id`=' . $user_id);
            print_r($user_id);
        };

    });

    Route::get('/testtt', function () {
            return View::make('instant.order.test');
        }
    );

    Route::get('/fsm_buy/{id?}', array('before' => 'auth', function ($id) {
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $fsm->apply('buy');
        try {
            $fsm->apply('pay_success');
        } catch (Exception $e) {
            $user = Auth::getUser();
            if ($instantOrder instanceof InstantOrder) {
                $iAmount = $instantOrder->quote_price;

                //添加一条充值记录
                $aRecharge = new Recharge();
                $aRecharge->user_id = $user['user_id'];
                $aRecharge->money = $iAmount;
                $aRecharge->type = 1; //支付方式
                $aRecharge->stat = 1; //初始化
                $aRecharge->createtime = time();
                $aRecharge->callback_action_id = $id;
                $aRecharge->callback_action_type = 1; //购买即时订单
                $aRecharge->save();
                $iRechargeID = $aRecharge->id;
            }
//        $iRechargeID = DB::table('gt_recharge')->insertGetId(array('user_id'=>$user['user_id'],'money'=>$iAmount,
//                'type'=>1,'stat'=>1,'createtime'=>time())

            //执行支付宝支付
            if (!empty ($iRechargeID) && !empty ($iAmount) && is_numeric($iAmount)) {
                $sHtmlText = Alipay::Payment($iAmount, sprintf("%08d", $iRechargeID), null, null, "付款", "付款");
                return $sHtmlText;
            }
        }
        return Redirect::to('instant_order_buyer');
    }));


    Route::get('/alipay_notify', array('before' => 'auth', function () {
        $aParams = $aError = array();
        $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
        $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
        $sPayNo = Input::get('trade_no'); //支付宝交易号
        $sTradeStatus = Input::get('trade_status'); //交易状态
        $sBuyer = Input::get('buyer_email');
        $notify = new Alipay;
        $bBes = $notify->notifyVerify(0x1003, intval($sTradeNo), $iMoney, $sPayNo, $sBuyer);
        if ($bBes) {
            return Redirect::to('instant_order_buyer');
        } else {
            echo 'fail';
        }
    }));

    Route::get('/alipay_return', array('before' => 'auth', function () {
        $aParams = $aError = array();
        $sTradeNo = Input::get('out_trade_no'); //获取支付宝传递过来的订单号
        $iMoney = Input::get('total_fee'); //获取支付宝传递过来的总价格
        $sPayNo = Input::get('trade_no'); //支付宝交易号
        $sTradeStatus = Input::get('trade_status'); //交易状态
        $sBuyer = Input::get('buyer_email');
        $notify = new Alipay;
        $bBes = $notify->returnVerify(0x1003, intval($sTradeNo), $iMoney, $sPayNo, $sBuyer);
        if ($bBes) {
            $rechargeId = intval($sTradeNo);
            $aRecharge = Recharge::where('id', '=', $rechargeId)->get();
            $instantOrderID = $aRecharge[0]->callback_action_id;
            $instantOrder = InstantOrder::findOrFail($instantOrderID);
            $fsm = new InstantOrderFsm($instantOrder);
            $fsm->apply('pay_success');
            return Redirect::to('instant_order_buyer');
        } else {
            echo 'fail';
        }
    }));

    Route::get('/recharge', array('before' => 'auth', function () {
        $user = Auth::getUser();
        if (Input::get('amount')) {
            $iAmount = intval(Input::get('amount'));
            //添加一条充值记录
            $iRechargeID = DB::table('gt_recharge')->insertGetId(array('user_id' => $user['user_id'], 'money' => $iAmount,
                    'type' => 1, 'stat' => 1, 'createtime' => time())
            );
            //执行支付宝支付
            if (!empty ($iRechargeID) && !empty ($iAmount) && is_numeric($iAmount)) {
                execApiPay($iRechargeID, $iAmount);
            } else {
                $aParams['iMoney'] = abs(Input::get('money'));
                $aParams['bUpgrade'] = Input::get('upgrade');
                $aParams['iVipCost'] = $iVipCost =
                    \Sports\Config\ConfigSingle::get('vip_recharge_amount_condition');
                $userBanlance = UserAccount::where('user_id', '=', $user['user_id'])->where('purpose', '=', 1);
                if ($userBanlance instanceof UserAccount) {
                    $aParams['iMoney'] = $iVipCost - $userBanlance->balance;
                }
                //$aParams['iMoney'] = $iVipCost - account_Api::userBanlance($iUserID);
            }
            return View::make('');
        }
        //跳转选择支付页面-->支付宝 网银


    }));
    Route::get('/test', array('before' => 'auth', function () {

        $user = Auth::getUser();
    }
    ));
});
Route::any('weixin_access', 'WeiXinController@index');







