<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class ReserveOrderFinance {

    /**
     * @var ReserveOrder|null
     */
    private $reserveOrder = null;
    public function __construct(ReserveOrder $reserveOrder) {
        $this->reserveOrder = $reserveOrder;
    }

    /**
     * 扣除金额
     */
    public function buy(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->reserveOrder->id)->setRelationType(FinanceConstant::RELATION_BOOKING);

        //扣除买方的钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->reserveOrder->user_id)->setAmount($this->reserveOrder->cost)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_CONSUME);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }
}
