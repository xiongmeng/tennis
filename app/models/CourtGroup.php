<?php

/**
 * Class CourtTemplate
 */
class CourtGroup extends Eloquent {
    protected $table = 'gt_hall_court';

    protected $fillable = array('name', 'count', 'hall_id');

}