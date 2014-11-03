<?php

class HallMap extends Eloquent {
    protected $table = 'gt_hall_map';
    protected $fillable = array('id','long', 'lat', 'baidu_code', 'hall_id');
}