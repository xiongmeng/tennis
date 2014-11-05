<?php

class HallMarket extends Eloquent {
    protected $table = 'gt_hall_market_week';
    protected $fillable = array('type', 'hall_id', 'start_week', 'end_week', 'start', 'end', 'price');

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id');
    }

    public function HallPrice(){
        return $this->hasOne('HallPrice', 'id', 'price');
    }

    public function Courts(){
        return $this->hasMany('Court', 'hall_id', 'hall_id');
    }

    public function CourtGroup(){
        return $this->hasOne('CourtGroup', 'hall_id', 'hall_id');
    }
}