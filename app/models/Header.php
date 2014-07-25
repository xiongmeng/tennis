<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Header extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gt_header';

    protected $primaryKey = 'header_id';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public function role()
    {
        return $this->belongsToMany('Header','gt_relation_header','header_id','p_id');
    }



}
