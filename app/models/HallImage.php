<?php

class HallImage extends Eloquent {
    protected $table = 'gt_hall_image';
    protected $fillable = array('hall_id', 'path');
}