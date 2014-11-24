<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpireTimeForSeeking extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_seeking', function(Blueprint $table){
            $table->integer('expire_time');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_seeking', function(Blueprint $table){
            $table->dropColumn('expire_time');
        });
	}

}
