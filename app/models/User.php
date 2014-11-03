<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gt_user_tiny';

    protected $primaryKey = 'user_id';

    protected $fillable = array('nickname', 'password', 'init_password');
    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    public function search($aQuery, $iPageSize = 20){
        $query = User::leftJoin('gt_relation_user_app as relation_weChat', function(\Illuminate\Database\Query\JoinClause $join){
            $join->on('relation_weChat.user_id', '=', 'gt_user_tiny.user_id')->where('relation_weChat.app_id', '=', APP_WE_CHAT);
        });
        if(!empty($aQuery['id'])){
            $query->where('gt_user_tiny.user_id', '=', $aQuery['id']);
        }
        if(!empty($aQuery['nickname'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['nickname'] . '%');
        }
        if(!empty($aQuery['telephone'])){
            $query->where('gt_user_tiny.telephone', 'like', '%' . $aQuery['telephone'] . '%');
        }
        if(!empty($aQuery['openid'])){
            $query->where('relation_weChat.app_user_id', 'like', '%' . $aQuery['openid'] . '%');
        }
        if(!empty($aQuery['is_bond_weChat'])){
            $aQuery['is_bond_weChat'] == YES ? $query->whereNotNull('relation_weChat.app_user_id') :
                $query->whereNull('relation_weChat.app_user_id');
        }
        if(!empty($aQuery['privilege'])){
            $query->where('gt_user_tiny.privilege', '=', $aQuery['privilege']);
        }
        return $query->orderBy('gt_user_tiny.user_id', 'desc')
            ->paginate($iPageSize, array('gt_user_tiny.*',
                'relation_weChat.app_user_id as weChat_open_id'));
    }

    public function roles(){
        return $this->hasMany('Role');
    }

    public function Halls(){
        return $this->belongsToMany('Hall', 'gt_relation_user_hall', 'user_id', 'hall_id')->withTimestamps();
    }

}
