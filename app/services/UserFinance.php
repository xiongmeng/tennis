<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class UserFinance {
    public function __construct() {
    }

    public function addBalanceFromRecharge(Recharge $recharge){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($recharge->id)->setRelationType(FinanceConstant::RELATION_RECHARGE);

        //增加余额
        $oAction = new ActionObject();
        $oAction->setUserId($recharge->user_id)->setAmount($recharge->pay_money)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }


}