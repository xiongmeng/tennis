<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTinyForLogout extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gt_user_tiny', function($table)
        {
            $table->string('remember_token',64);
            $table->string('updated_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_user_tiny', function($table)
        {
            $table->dropColumn('remember_token','updated_at');
        });
	}

}
