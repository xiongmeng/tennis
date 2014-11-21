<?php
class SeekingOrderManager {

    private $fsm = null;

    public function __construct() {
        $this->fsm = new SeekingOrderFsm();
    }

    public function checkPay($seekingOrders, $payUserId = null){
        if (!$seekingOrders instanceof \Illuminate\Database\Eloquent\Collection) {
            $countOri = count($seekingOrders);
            $seekingOrders = SeekingOrder::whereIn('id', $seekingOrders)->get();
            if (count($seekingOrders) != $countOri) {
                throw new Exception(sprintf('选取了不存在的约球单：选择(%d)，实际(%d)', count($seekingOrders), $countOri));
            }
        }

        //获取总共需要支付的钱数
        $needPay = 0;
        foreach ($seekingOrders as $seekingOrder) {
            $needPay += $seekingOrder->cost;
        }
        $result['needPay'] = $needPay;

        //获取用户当前余额
        if(empty($payUserId)){
            $user = Auth::getUser();
            $payUserId = $user->user_id;
        }

        $account = Finance::ensureAccountExisted($payUserId, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $result['balance'] = $account->getAvailableAmount();

        $result['needRecharge'] = $needPay - $result['balance'];

        //如果可用余额不够 进支付宝，否则轮询支付
        if ($result['needRecharge'] > 0) {
            $seekingOrderIds = array();
            foreach($seekingOrders as $seekingOrder){
                $seekingOrderIds[] = $seekingOrder->id;
            }

            //预先生成recharge表
            $recharge = new Recharge();
            $recharge->generate($result['needRecharge'], $payUserId,
                RECHARGE_CALLBACK_PAY_RESERVE_ORDER, implode(',', $seekingOrderIds));

            no_money_generate_url($result, $recharge);

            $result['status'] = 'no_money';
        }else{
            $result['status'] = 'can_pay';
        }
        return $result;
    }

    public function batchPay($seekingOrders, $payUserId = null){
        if (!$seekingOrders instanceof \Illuminate\Database\Eloquent\Collection) {
            $countOri = count($seekingOrders);
            $seekingOrders = SeekingOrder::whereIn('id', $seekingOrders)->get();
            if (count($seekingOrders) != $countOri) {
                throw new Exception(sprintf('选取了不存在的约球单：选择(%d)，实际(%d)', count($seekingOrders), $countOri));
            }
        }

        $result = $this->checkPay($seekingOrders, $payUserId);
        if($result['status'] == 'can_pay'){
            $this->fsm->batchPay($seekingOrders);
            $result['adviseForwardUrl'] = '/instant_order_buyer';

            $result['status'] = 'pay_success';
        }

        return $result;
    }
}
