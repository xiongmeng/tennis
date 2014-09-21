<?php

/**
 * Class CourtTemplate
 */
class weChatUserProfile extends Eloquent {
    protected $table = 'gt_wechat_user_profile';

    protected $primaryKey = 'openid';

    public $fillable = array('nickname', 'openid', 'sex', 'province','city','country','headimgurl', 'privilege');
}