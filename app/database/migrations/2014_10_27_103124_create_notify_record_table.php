<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifyRecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('gt_notify_record', function(Blueprint $table){
            $table->increments('id');
            $table->integer('event');
            $table->integer('object');
            $table->string('channel');
            $table->string('who');
            $table->string('msg', 1024);
            $table->string('result', 1024);
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
		Schema::drop('gt_notify_record');
	}

}
