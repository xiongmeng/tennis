<?php
class FinanceCustom extends Eloquent {
    protected $table = 'gt_finance_custom';

    function generate($debtor, $creditor, $amount, $reason){
        $this->debtor = $debtor;
        $this->creditor = $creditor;
        $this->amount = $amount;
        $this->reason = $reason;
        $this->createtime = time();
        $this->stat = FINANCE_CUSTOM_INIT;
        $this->save();
    }
}