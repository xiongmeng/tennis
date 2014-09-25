<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class ReserveOrderManager
{
    private $fsm = null;

    public function __construct()
    {
        $this->fsm = new ReserveOrderFsm();
    }

    public function batchPay($reserves, $payUserId = null){
        if (!$reserves instanceof \Illuminate\Database\Eloquent\Collection) {
            $reserves = ReserveOrder::whereIn('id', $reserves)->get();
            if (count($reserves) != count($reserves)) {
                throw new Exception(sprintf('选取了不存在的预约订单：选择(%d)，实际(%d)', count($reserves), count($reserves)));
            }
        }

        //获取总共需要支付的钱数
        $needPay = 0;
        foreach ($reserves as $reserve) {
            $needPay += $reserve->cost;
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
            $reserveOrderIds = array();
            foreach($reserves as $reserve){
                $reserveOrderIds[] = $reserve->id;
            }

            //预先生成recharge表
            $recharge = new Recharge();
            $recharge->generate($result['needRecharge'], $payUserId,
                RECHARGE_CALLBACK_PAY_RESERVE_ORDER, implode(',', $reserveOrderIds));

            no_money_generate_url($result, $recharge);

            $result['status'] = 'no_money';
        } else {
            $this->fsm->batchPay($reserves);
            $result['adviseForwardUrl'] = '/instant_order_buyer';

            $result['status'] = 'pay_success';
        }

        return $result;
    }
}