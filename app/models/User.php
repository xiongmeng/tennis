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

    protected $fillable = array('nickname', 'password');
    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    public function search($aQuery, $iPageSize = 10){
        return User::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['nickname'])){
                $builder->where('nickname', 'like', '%' . $aQuery['nickname'] . '%');
            }
            if(!empty($aQuery['telephone'])){
                $builder->where('telephone', 'like', '%' . $aQuery['telephone'] . '%');
            }
        })
        ->paginate($iPageSize);
    }

    public function roles(){
        return $this->belongsToMany('Role', 'gt_relation_user_role', 'user_id', 'role_id');
    }

    public function Halls(){
        return $this->belongsToMany('Hall', 'gt_relation_user_hall', 'user_id', 'hall_id')->withTimestamps();
    }

}
