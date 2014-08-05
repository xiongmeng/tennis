<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class BillingStaging extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gt_account_billing_staging';

    public function search($aQuery, $iPageSize = 10){
        return BillingStaging::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['user_id'])){
                $builder->where('user_id', '=', $aQuery['user_id']);
            }
            if(!empty($aQuery['purpose'])){
                $builder->where('purpose', '=', $aQuery['purpose']);
            }
            if(!empty($aQuery['billing_created_time_start'])){
                $builder->where('billing_created_time', '>=', strtotime($aQuery['billing_created_time_start']));
            }
            if(!empty($aQuery['billing_created_time_end'])){
                $builder->where('billing_created_time', '<=', strtotime($aQuery['billing_created_time_end']));
            }
            if(!empty($aQuery['hall_id'])){
                is_array($aQuery['hall_id']) ? $builder->getQuery()->whereIn('hall_id', $aQuery['hall_id']) :
                    $builder->where('hall_id', '=', $aQuery['hall_id']);
            }
            if(!empty($aQuery['relation_type'])){
                is_array($aQuery['relation_type']) ? $builder->getQuery()->whereIn('relation_type', $aQuery['relation_type']) :
                    $builder->where('relation_type', '=', $aQuery['relation_type']);
            }
            if(!empty($aQuery['relation_id'])){
                is_array($aQuery['relation_id']) ? $builder->getQuery()->whereIn('relation_id', $aQuery['relation_id']) :
                    $builder->where('relation_id', '=', $aQuery['relation_id']);
            }
            if(!empty($aQuery['user_name'])){
                $builder->where('user_name', 'like', '%' . $aQuery['nickname'] . '%');
            }
            if(!empty($aQuery['telephone'])){
                $builder->where('telephone', 'like', '%' . $aQuery['telephone'] . '%');
            }
        })->orderBy('billing_created_time', 'desc')
        ->paginate($iPageSize);
    }


}
