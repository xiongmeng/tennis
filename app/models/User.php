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

    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    public function search($aQuery){
        return User::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['nickname'])){
                $builder->where('nickname', 'like', '%' . $aQuery['nickname'] . '%');
            }
            if(!empty($aQuery['telephone'])){
                $builder->where('telephone', 'like', $aQuery['telephone']);
            }
        })
        ->paginate(2);
    }
}
