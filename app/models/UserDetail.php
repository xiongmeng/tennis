<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 14-7-28
 * Time: 下午12:41
 */
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserDetail extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gt_user_detail';

    protected $primaryKey = 'user_id';
}