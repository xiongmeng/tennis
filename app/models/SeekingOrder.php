<?php

class SeekingOrder extends Eloquent implements \Finite\StatefulInterface{

    protected $table = 'gt_seeking_order';
    protected $fillable = array('state', 'seeking_id', 'seeker', 'participant');
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

}