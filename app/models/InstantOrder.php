<?php

class InstantOrder extends Eloquent implements \Finite\StatefulInterface {

    protected $table = 'gt_instant_order';
    protected $primaryKey = 'id';
    /**
     * Sets the object state
     *
     * @return string
     */
    public function getFiniteState(){
        return $this->state;
    }

    /**
     * Sets the object state`
     *
     * @param string $state
     */
    public function setFiniteState($state){
        $this->state = $state;
        $this->save();
    }

    public function search($aQuery, $iPageSize =20){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['id'])){
                $builder->where('id', '=', $aQuery['id']);
            }
            if(!empty($aQuery['expire_time_start'])){
                $builder->where('expire_time', '>', $aQuery['expire_time_start']);
            }
            if(!empty($aQuery['seller'])){
                $builder->where('seller', '=', $aQuery['seller']);
            }
            if(!empty($aQuery['buyer'])){
                $builder->where('buyer', '=', $aQuery['buyer']);
            }
            if(!empty($aQuery['event_date_start'])){
                $builder->where('event_date', '>=', $aQuery['event_date_start']);
            }
            if(!empty($aQuery['event_date_end'])){
                $builder->where('event_date', '<=', $aQuery['event_date_end']);
            }
            if(!empty($aQuery['buyer_name'])){
                $builder->where('buyer_name', 'like', '%' . $aQuery['buyer_name'] . '%');
            }
            if(!empty($aQuery['hall_name'])){
                $builder->where('hall_name', 'like', '%' . $aQuery['hall_name'] . '%');
            }
            if(!empty($aQuery['state'])){
                is_array($aQuery['state']) ? $builder->getQuery()->whereIn('state', $aQuery['state'])
                    : $builder->where('state', '=', $aQuery['state']);
            }

        })->orderBy('event_date', 'desc')
            ->paginate($iPageSize);
    }

    public function searchHallPriceAggregate($aQuery, $iPageSize =20){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['event_date_start'])){
                $builder->where('event_date', '>=', $aQuery['event_date_start']);
            }
            if(!empty($aQuery['event_date'])){
                $builder->where('event_date', '=', $aQuery['event_date']);
            }
            if(!empty($aQuery['start_hour'])){
                $builder->where('start_hour', '=', $aQuery['start_hour']);
            }
            if(!empty($aQuery['hall_name'])){
                $builder->where('hall_name', 'like', '%' . $aQuery['hall_name'] . '%');
            }
            if(!empty($aQuery['state'])){
                is_array($aQuery['state']) ? $builder->getQuery()->whereIn('state', $aQuery['state'])
                    : $builder->where('state', '=', $aQuery['state']);
            }
        })->groupBy(array('hall_id', 'quote_price'))->orderBy('quote_price', 'asc')
            ->paginate($iPageSize, array('hall_id','quote_price','event_date','start_hour','court_id', DB::raw('COUNT(1) AS count')));
    }

    public function User(){
        return $this->belongsTo('User', 'user_id', 'user_id');
    }

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id', 'id');
    }
}