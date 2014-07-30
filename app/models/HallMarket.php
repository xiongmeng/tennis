<?php

class HallMarket extends Eloquent {
    protected $table = 'gt_hall_market_week';

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id');
    }

    public function HallPrice(){
        return $this->hasOne('HallPrice', 'id', 'price');
    }

    public function Court(){
        return $this->hasMany('Court', 'hall_id', 'hall_id');
    }

    public function CourtGroup(){
        return $this->hasOne('CourtGroup', 'hall_id', 'hall_id');
    }
}