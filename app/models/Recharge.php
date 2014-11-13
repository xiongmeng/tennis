<?php

class Recharge extends Eloquent {
    protected $table = 'gt_recharge';

    function generate($money, $payUserId=null, $actionType='', $actionToken='', $appUserId='', $appId=''){
        APP::environment('local') && $this->id = time();
        //预先生成recharge表
        $this->user_id = (empty($payUserId) ? user_id() : $payUserId);
        $this->money = $money;
        $this->stat = 1; //初始化
        $this->callback_action_token = $actionToken;
        $this->callback_action_type = $actionType; //购买预约订单
        $this->app_user_id = $appUserId;
        $this->app_id = (empty($appId) ? app_id() : $appId);
        $this->type = PAY_TYPE_MGR; //默认为管理员线下充值
        $this->save();
    }

    public function User(){
        return $this->belongsTo('User', 'user_id', 'user_id');
    }

    public function search($aQuery, $iPageSize =20){
        $query = Recharge::leftJoin('gt_user_tiny', 'gt_user_tiny.user_id', '=', 'gt_recharge.user_id');
        if(!empty($aQuery['id'])){
            $query->where('gt_recharge.id', '=', $aQuery['id']);
        }
        if(isset($aQuery['stat'])){
            if(is_array($aQuery['stat'])){
                $query->whereIn('gt_recharge.stat', $aQuery['stat']);
            }else if(!empty($aQuery['stat'])){
                $query->where('gt_recharge.stat', '=', $aQuery['stat']);
            }
        }
        if(isset($aQuery['type'])){
            if(is_array($aQuery['type'])){
                $query->whereIn('gt_recharge.type', $aQuery['type']);
            }else if(!empty($aQuery['type'])){
                $query->where('gt_recharge.type', '=', $aQuery['type']);
            }
        }
        if(!empty($aQuery['user_name'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['user_name'] . '%');
        }
        return $query->orderBy('gt_recharge.id', 'desc')
            ->paginate($iPageSize, array('gt_recharge.*', 'gt_user_tiny.nickname as user_name'));
    }
}