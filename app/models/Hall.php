<?php

class Hall extends Eloquent {
    protected $table = 'gt_hall_tiny';

    public function CourtGroup(){
        return $this->hasOne('CourtGroup', 'hall_id');
    }

    public function HallMarkets(){
        return $this->hasMany('HallMarket', 'hall_id');
    }

    public function HallPrices(){
        return $this->hasMany('HallPrice', 'hall_id');
    }

    public function Users(){
        return $this->belongsToMany('User', 'gt_relation_user_hall', 'hall_id', 'user_id');
    }
}