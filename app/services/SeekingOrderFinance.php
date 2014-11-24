<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class SeekingOrderFinance {

    /**
     * @var SeekingOrder|null
     */
    private $seekingOrder = null;

    public function __construct(SeekingOrder $seekingOrder) {
        $this->seekingOrder = $seekingOrder;
    }

    /**
     * 扣除金额
     */
    public function buy(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->seekingOrder->id)->setRelationType(FINANCE_RELATION_BUY_SEEKING_ORDER);

        //扣除买方的钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->seekingOrder->joiner)->setAmount($this->seekingOrder->cost)
            ->setPurpose(FINANCE_PURPOSE_ACCOUNT)->setOperateType(FINANCE_OPERATE_CONSUME);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 取消预约订单
     */
    public function cancel(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->seekingOrder->id)->setRelationType(FINANCE_RELATION_CANCEL_SEEKING_ORDER);

        //给买方加钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->seekingOrder->joiner)->setAmount($this->seekingOrder->cost)
            ->setPurpose(FINANCE_PURPOSE_ACCOUNT)->setOperateType(FINANCE_OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }
}
