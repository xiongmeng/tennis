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

    private function checkIsDisposed(){

    }

    /**
     * 冻结金额
     */
    public function freeze(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_BUY_INSTANT_ORDER);

        //冻结买方钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_FREEZE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 解冻买方钱
     */
    public function cancel(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_CANCEL_INSTANT_ORDER);

        //解冻买方钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_UNFREEZE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 解冻买方钱
     */
    public function terminate(){
        $oOperate = new OperateObject();
        $oOperate->setRelationId($this->instantOrder->id)->setRelationType(FinanceConstant::RELATION_TERMINATE_INSTANT_ORDER);

        //解冻买方钱
        $oAction = new ActionObject();
        $oAction->setUserId($this->instantOrder->buyer)->setAmount($this->instantOrder->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_UNFREEZE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }

    /**
     * 执行
     * - 解冻买家钱
     * - 扣除买家钱
     * - 卖家增加钱
     */
    public function execute(){
        $order = $this->instantOrder;
        $oOperate = new OperateObject();
        $oOperate->setRelationId($order->id)->setRelationType(FinanceConstant::RELATION_BUY_INSTANT_ORDER);

        //解冻买方钱
        $oAction = new ActionObject();
        $oAction->setUserId($order->buyer)->setAmount($order->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_UNFREEZE);
        $oOperate->addAction($oAction);

        //扣除买家钱
        $oAction = new ActionObject();
        $oAction->setUserId($order->buyer)->setAmount($order->quote_price)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_CONSUME);
        $oOperate->addAction($oAction);

        //给卖家加钱
        $oAction = new ActionObject();
        $oAction->setUserId($order->seller)->setAmount($order->quote_price)
            ->setRelationType(FinanceConstant::RELATION_SELL_INSTANT_ORDER)
            ->setPurpose(FinanceConstant::PURPOSE_ACCOUNT)->setOperateType(FinanceConstant::OPERATE_RECHARGE);
        $oOperate->addAction($oAction);

        Finance::execute($oOperate);
    }
}