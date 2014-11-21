<?php

class SeekingOrder extends Eloquent implements \Finite\StatefulInterface{

    protected $table = 'gt_seeking_order';
    protected $fillable = array('state', 'seeking_id', 'seeker', 'joiner', 'cost');
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

    public function Joiner(){
        return $this->hasOne('User', 'user_id', 'joiner');
    }

    public function search($aQuery, $iPageSize){
        $query = SeekingOrder::leftJoin('gt_seeking', 'gt_seeking_order.seeking_id', '=', 'gt_seeking.id')
            ->leftJoin('gt_hall_tiny', 'gt_hall_tiny.id', '=', 'gt_seeking.hall_id')
            ->leftJoin('gt_user_tiny', 'gt_user_tiny.user_id', '=', 'gt_seeking.creator');

        if(!empty($aQuery['joiner_id'])){
            $query->where('gt_seeking_order.joiner', '=', $aQuery['joiner_id']);
        }
        if(isset($aQuery['state'])){
            if(is_array($aQuery['state'])){
                $query->whereIn('gt_seeking_order.state', $aQuery['state']);
            }else {
                $query->where('gt_seeking_order.state', '=', $aQuery['state']);
            }
        }
        if(!empty($aQuery['seeking_id'])){
            $query->where('gt_seeking_order.seeking_id', '=', $aQuery['seeking_id']);
        }
        if(!empty($aQuery['hall_name'])){
            $query->where('gt_hall_tiny.name', 'like', '%' . $aQuery['hall_name'] . '%');
        }
        if(!empty($aQuery['event_date_start'])){
            $query->where('gt_seeking.event_date', '>=', $aQuery['event_date_start']);
        }
        if(!empty($aQuery['event_date_end'])){
            $query->where('gt_seeking.event_date', '<=', $aQuery['event_date_end']);
        }
        if(!empty($aQuery['creator_name'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['creator_name'] . '%');
        }
        if(isset($aQuery['seeking_state'])){
            if(is_array($aQuery['seeking_state'])){
                $query->whereIn('gt_seeking.state', $aQuery['seeking_state']);
            }else {
                $query->where('gt_seeking.state', '=', $aQuery['seeking_state']);
            }
        }
        return $query->orderBy('gt_seeking.id', 'desc')
            ->paginate($iPageSize, array('gt_seeking_order.*', 'gt_seeking.event_date', 'gt_seeking.start_hour',
                'gt_seeking.end_hour','gt_seeking.hall_id','gt_seeking.court_num', 'gt_hall_tiny.name as hall_name', 'gt_user_tiny.nickname as creator_name'));
    }
}