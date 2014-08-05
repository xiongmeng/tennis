<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Role extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gt_relation_user_role';

    protected $primaryKey = 'role_id';

    protected $fillable = array('role_id');

    public function headers(){
        return $this->belongsToMany('Header', 'gt_relation_role_header', 'role_id', 'header_id');
    }
}