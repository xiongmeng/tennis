<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 14-7-28
 * Time: 下午2:39
 */
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Order extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gt_order';

    protected $primaryKey = 'user_id';
}