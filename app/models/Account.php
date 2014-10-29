<?php
class Account extends Eloquent {
    protected $table = 'gt_account';

    public function search($aQuery, $iPageSize = 20){
        $query = Account::leftJoin('gt_user_tiny', 'gt_account.user_id', '=', 'gt_user_tiny.user_id');
        if(!empty($aQuery['id'])){
            $query->where('gt_account.id', '=', $aQuery['id']);
        }
        if(!empty($aQuery['nickname'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['nickname'] . '%');
        }
        if(!empty($aQuery['telephone'])){
            $query->where('gt_user_tiny.telephone', 'like', '%' . $aQuery['telephone'] . '%');
        }
        if(!empty($aQuery['balance_lower_bound'])){
            $query->where('gt_account.balance', '>=', $aQuery['balance_lower_bound']);
        }
        if(!empty($aQuery['balance_upper_bound'])){
            $query->where('gt_account.balance', '<=', $aQuery['balance_upper_bound']);
        }
        if(!empty($aQuery['purpose'])){
            $query->where('gt_account.purpose', '=', $aQuery['purpose']);
        }
        return $query->orderBy('gt_account.id', 'desc')
            ->paginate($iPageSize);
    }
}