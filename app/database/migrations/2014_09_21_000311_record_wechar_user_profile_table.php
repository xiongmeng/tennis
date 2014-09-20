<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecordWecharUserProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE gt_user_tiny MODIFY COLUMN `nickname` char(255) BINARY DEFAULT NULL");

        Schema::create('gt_wechat_user_profile', function(Blueprint $table){

            //billing表info
            $table->integer('id');
            $table->string('openid');
            $table->string('nickname');
            $table->integer('sex');
            $table->string('province');
            $table->string('city');
            $table->string('country');
            $table->string('headimgurl');
            $table->string('privilege', 1024);

            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gt_wechat_user_profile');

        DB::statement("ALTER TABLE gt_user_tiny MODIFY COLUMN `nickname` char(32) BINARY DEFAULT NULL");
    }
}
