<?php
class ReserveOrder extends Eloquent {
    protected $table = 'gt_order';

    public function User(){
        return $this->belongsTo('User', 'user_id', 'user_id');
    }

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id', 'id');
    }
}