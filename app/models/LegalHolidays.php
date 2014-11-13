<?php

class LegalHolidays extends Eloquent {
    protected $table = 'gt_legal_holidays';
    protected $fillable = array('type', 'date');
}