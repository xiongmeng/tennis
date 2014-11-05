<?php

class HallPrice extends Eloquent {
    protected $table = 'gt_hall_price';

    protected $fillable = array('court_type', 'hall_id', 'market', 'member', 'name', 'purchase', 'vip');
}