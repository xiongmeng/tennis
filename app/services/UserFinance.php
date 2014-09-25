<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class UserFinance
{
    public function __construct()
    {
    }

    public function addBalanceFromRecharge(Recharge $recharge)
    {
        $oOperate = new OperateObject();
        $oOperate->setRelationId($recharge->id)->setRelationType(FinanceConstant::RELATION_RECHARGE);

        //增加余额
        $oAction = new ActionObject();
        $oAction->setUserId($recharge->user_id)->setAmount($recharge->pay_money)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }


    public function doPaySuccess($recharge, $token, $payMoney)
    {
        if (!$recharge instanceof Recharge) {
            $recharge = Recharge::findOrFail($recharge);
        }

        //修改充值结果
        $affectedRows = Recharge::whereId($recharge->id)->whereStat(1)->update(array('stat' => 2,
            'sToken' => $token, 'pay_money' => debug() ? $recharge->money : $payMoney));


        if ($affectedRows == 1) {
            $recharge = Recharge::findOrFail($recharge->id);

            //执行一次充值
            $this->addbalancefromrecharge($recharge);

            DB::beginTransaction();
            try {
                $this->doCallback($recharge);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('business_error_when_pay_success', array('code' => $e->getCode(),
                    'msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()));
            }
        }
    }

    function doCallback(Recharge $recharge){
        $actionType = $recharge->callback_action_type;
        switch ($actionType) {
            //即时订单的扣费
            case RECHARGE_CALLBACK_PAY_INSTANT_ORDER:
                $fsm = new InstantOrderFsm();
                $fsm->batchPay($recharge->callback_action_token);
                break;
            //预约订单扣费
            case RECHARGE_CALLBACK_PAY_RESERVE_ORDER:
                $fsm = new ReserveOrderFsm();
                $fsm->batchPay($recharge->callback_action_token);
                break;
            //升级成为VIP
            default:
                $balance = balance($recharge->user_id);
                if ($balance >= UPGRADE_TO_GOLD_MONEY) {
                    User::whereUserId($recharge->user_id)->wherePrivilege(PRIVILEGE_NORMAL)
                        ->update(array('privilege' => PRIVILEGE_GOLD));
                }
                break;
        }
    }
}