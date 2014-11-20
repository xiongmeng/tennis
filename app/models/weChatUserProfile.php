<?php
use Illuminate\Database\Query\JoinClause;
/**
 * Class CourtTemplate
 */
class weChatUserProfile extends Eloquent {
    protected $table = 'gt_wechat_user_profile';

    protected $primaryKey = 'openid';

    public $fillable = array('nickname', 'openid', 'sex', 'province','city','country','headimgurl', 'privilege');

    public function search($aQuery, $iPageSize = 20){
        $query = weChatUserProfile::leftJoin('gt_relation_user_app', function(JoinClause $join){
            $join->on('gt_relation_user_app.app_user_id', '=', 'gt_wechat_user_profile.openid')
                ->where('gt_relation_user_app.app_id', '=', APP_WE_CHAT);
        });
        if(!empty($aQuery['user_id'])){
            $query->where('gt_relation_user_app.user_id', '=', $aQuery['user_id']);
        }
        if(!empty($aQuery['openid'])){
            $query->where('gt_wechat_user_profile.openid', 'like', '%' . $aQuery['openid'] . '%');
        }
        if(!empty($aQuery['nickname'])){
            $query->where('gt_wechat_user_profile.nickname', '=', $aQuery['nickname']);
        }
        return $query->orderBy('gt_wechat_user_profile.created_at', 'desc')
            ->paginate($iPageSize, array('gt_wechat_user_profile.*', 'gt_relation_user_app.user_id as user_id'));
    }
}