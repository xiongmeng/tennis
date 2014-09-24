<?php

class ReserveOrder extends Eloquent implements \Finite\StatefulInterface{

    protected $table = 'gt_order';
    /**
     * Sets the object state
     *
     * @return string
     */
    public function getFiniteState(){
        return $this->stat;
    }

    /**
     * Sets the object state`
     *
     * @param string $state
     */
    public function setFiniteState($stat){
        $this->stat = $stat;
        $this->save();
    }

    public function User(){
        return $this->belongsTo('User', 'user_id', 'user_id');
    }

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id', 'id');
    }
}