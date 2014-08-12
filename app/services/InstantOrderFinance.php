<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class InstantOrderFinance {

    /**
     * @var InstantOrder|null
     */
    private $instantOrder = null;
    public function __construct(InstantOrder $instantOrder) {
        $this->instantOrder = $instantOrder;
    }

    /**
     * 扣除金额
     */
    public function buy(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_BUY_INSTANT_ORDER);

        //扣除买方的钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_CONSUME);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 返回买方的钱
     */
    public function cancel(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_CANCEL_INSTANT_ORDER);

        //返还买方钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 返回买方的钱
     */
    public function terminate(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_TERMINATE_INSTANT_ORDER);

        //返回买方的钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 执行
     * - 卖家增加钱
     */
    public function execute(){
        $order = $this->instantOrder;
        $oOperate = new OperateObject();
        $oOperate->setRelationId($order->id)->setRelationType(FinanceConstant::RELATION_SELL_INSTANT_ORDER);

        //给卖家加钱
        $oAction = new ActionObject();
        $oAction->setUserId($order->seller)->setAmount($order->cost_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }
}