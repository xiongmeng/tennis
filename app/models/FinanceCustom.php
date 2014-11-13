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

    public function search($aQuery, $iPageSize =20){
        $query = FinanceCustom::leftJoin('gt_user_tiny', 'gt_user_tiny.user_id', '=', 'gt_finance_custom.debtor');
        if(!empty($aQuery['id'])){
            $query->where('gt_finance_custom.id', '=', $aQuery['id']);
        }
        if(!empty($aQuery['user_name'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['user_name'] . '%');
        }
        return $query->orderBy('gt_finance_custom.id', 'desc')
            ->paginate($iPageSize, array('gt_finance_custom.*', 'gt_user_tiny.nickname as user_name'));
    }
}