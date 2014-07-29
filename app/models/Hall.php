<?php

class Hall extends Eloquent {
    protected $table = 'gt_hall_tiny';

    public function CourtGroup(){
        return $this->hasOne('CourtGroup', 'hall_id');
    }

    public function HallMarket(){
        return $this->hasMany('HallMarket', 'hall_id');
    }

    public function HallPrice(){
        return $this->hasMany('HallPrice', 'hall_id');
    }
}