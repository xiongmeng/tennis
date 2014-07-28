<?php

class InstantOrder extends Eloquent implements \Finite\StatefulInterface {

    protected $table = 'gt_instant_order';

    /**
     * Sets the object state
     *
     * @return string
     */
    public function getFiniteState(){
        return $this->state;
    }

    /**
     * Sets the object state
     *
     * @param string $state
     */
    public function setFiniteState($state){
        $this->state = $state;
        $this->save();
    }
}