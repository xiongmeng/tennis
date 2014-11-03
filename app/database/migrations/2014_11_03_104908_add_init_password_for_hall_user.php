<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitPasswordForHallUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_user_tiny', function(Blueprint $table){
           $table->string('init_password');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gt_user_tiny', function(Blueprint $table){
            $table->dropColumn('init_password');
        });
	}

}
