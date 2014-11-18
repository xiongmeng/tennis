<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeekingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('gt_seeking', function(Blueprint $table){
            $table->integer('id', true);
            $table->char('state', 16);
            $table->timestamp('event_date');    //活动日期
            $table->integer('start_hour');  //开始时间
            $table->integer('end_hour');    //结束时间
            $table->integer('hall_id');     //场馆id
            $table->string('hall_name');    //场馆名称
            $table->string('court_num');    //场地片数
            $table->string('tennis_level');        //级别-1.0,2.0...
            $table->string('content');    //活动内容-单打，联系，等
            $table->integer('sexy');        //性别要求
            $table->integer('personal_cost');        //活动人均费用
            $table->integer('store');       //总坑数
            $table->integer('sold');        //已占坑数
            $table->integer('on_sale');     //剩余坑数
            $table->integer('creator');     //创建者
            $table->string('comment');        //活动备注
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gt_seeking');
    }

}
