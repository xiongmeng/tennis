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
        $this->save();
    }
}