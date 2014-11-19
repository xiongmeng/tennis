<?php

class Seeking extends Eloquent implements \Finite\StatefulInterface{

    protected $table = 'gt_seeking';
    protected $fillable = array('event_date', 'start_hour', 'end_hour', 'hall_id', 'court_num', 'state', 'creator',
        'tennis_level', 'sexy', 'sold', 'on_sale', 'store', 'personal_cost', 'content', 'comment');
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

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id', 'id');
    }

    public function Joiners(){
        return $this->belongsToMany('User', 'gt_seeking_order', 'seeking_id', 'joiner')->withTimestamps();
    }

    public function search($aQuery, $iPageSize =20){
        $query = Seeking::leftJoin('gt_hall_tiny', 'gt_hall_tiny.id', '=', 'gt_seeking.hall_id')
            ->leftJoin('gt_user_tiny', 'gt_user_tiny.user_id', '=', 'gt_seeking.creator');
        if(!empty($aQuery['id'])){
            $query->where('gt_seeking.id', '=', $aQuery['id']);
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
        if(isset($aQuery['state'])){
            if(is_array($aQuery['state'])){
                $query->whereIn('gt_seeking.state', $aQuery['state']);
            }else {
                $query->where('gt_seeking.state', '=', $aQuery['state']);
            }
        }
        return $query->orderBy('gt_seeking.id', 'desc')
            ->paginate($iPageSize, array('gt_seeking.*',
                'gt_hall_tiny.name as hall_name', 'gt_user_tiny.nickname as creator_name'));
    }
}