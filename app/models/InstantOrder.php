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

    public function search($aQuery, $iPageSize =25){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['id'])){
                $builder->where('id', 'like', '%' . $aQuery['id'] . '%');
            }
            if(!empty($aQuery['hall_id'])){
                $builder->where('hall_id', 'like', '%' . $aQuery['hall_id'] . '%');
            }

            if(!empty($aQuery['event_date'])){
                $builder->where('event_date', 'like', '%' . $aQuery['event_date'] . '%');
            }
            if(!empty($aQuery['start_hour'])){
                $builder->where('start_hour', 'like', '%' . $aQuery['start_hour'] . '%');
            }
            if(!empty($aQuery['end_hour'])){
                $builder->where('end_hour', 'like', '%' . $aQuery['end_hour'] . '%');
            }
            if(!empty($aQuery['quote_price'])){
                $builder->where('quote_price', 'like', '%' . $aQuery['quote_price'] . '%');
            }
            if(!empty($aQuery['seller'])){
                $builder->where('seller', 'like', '%' . $aQuery['seller'] . '%');
            }
            if(!empty($aQuery['buyer_name'])){
                $builder->where('buyer_name', 'like', '%' . $aQuery['buyer_name'] . '%');
            }
            if(!empty($aQuery['state'])){
                $builder->where('state', 'like', '%' . $aQuery['state'] . '%');

            }
        })
            ->paginate($iPageSize);
    }
    public function hall($hallID,$courtID){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($hallID,$courtID){
            if($hallID){
                $builder->where('hall_id', '='. $hallID);
            }
            if($courtID){
                $builder->where('court_id', '='. $courtID);
            }
        })->get();
    }
    public function on_sale($aQuery,$iPageSize =20){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){

             $builder->where('expire_time','>',time())->get();

            if(!empty($aQuery['hall_id'])){
                $builder->where('hall_id', 'like', '%' . $aQuery['hall_id'] . '%');
            }
            if(!empty($aQuery['event_date'])){
                $builder->where('event_date', 'like', '%' . $aQuery['event_date'] . '%');
            }
            if(!empty($aQuery['start_hour'])){
                $builder->where('start_hour', 'like', '%' . $aQuery['start_hour'] . '%');
            }
            if(!empty($aQuery['end_hour'])){
                $builder->where('end_hour', 'like', '%' . $aQuery['end_hour'] . '%');
            }
            if(!empty($aQuery['quote_price'])){
                $builder->where('quote_price', 'like', '%' . $aQuery['quote_price'] . '%');
            }

        })
            ->paginate($iPageSize);
    }
    public function buyer($aQuery,$userID,$iPageSize =20){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery,$userID){

            $builder->where('buyer','=',$userID)->get();

            if(!empty($aQuery['hall_id'])){
                $builder->where('hall_id', 'like', '%' . $aQuery['hall_id'] . '%');
            }
            if(!empty($aQuery['event_date'])){
                $builder->where('event_date', 'like', '%' . $aQuery['event_date'] . '%');
            }
            if(!empty($aQuery['start_hour'])){
                $builder->where('start_hour', 'like', '%' . $aQuery['start_hour'] . '%');
            }
            if(!empty($aQuery['end_hour'])){
                $builder->where('end_hour', 'like', '%' . $aQuery['end_hour'] . '%');
            }
            if(!empty($aQuery['quote_price'])){
                $builder->where('quote_price', 'like', '%' . $aQuery['quote_price'] . '%');
            }

        })
            ->paginate($iPageSize);
    }
    public function seller($aQuery,$userID,$iPageSize =20){
        return InstantOrder::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery,$userID){

            $builder->where('seller','=',$userID)->get();

            if(!empty($aQuery['hall_id'])){
                $builder->where('hall_id', 'like', '%' . $aQuery['hall_id'] . '%');
            }
            if(!empty($aQuery['event_date'])){
                $builder->where('event_date', 'like', '%' . $aQuery['event_date'] . '%');
            }
            if(!empty($aQuery['start_hour'])){
                $builder->where('start_hour', 'like', '%' . $aQuery['start_hour'] . '%');
            }
            if(!empty($aQuery['end_hour'])){
                $builder->where('end_hour', 'like', '%' . $aQuery['end_hour'] . '%');
            }
            if(!empty($aQuery['quote_price'])){
                $builder->where('quote_price', 'like', '%' . $aQuery['quote_price'] . '%');
            }

        })
            ->paginate($iPageSize);
    }
}