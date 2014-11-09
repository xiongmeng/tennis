<?php

class HallActive extends Eloquent {
    protected $table = 'gt_hall_active';
    protected $fillable = array('hall_id', 'type');
}