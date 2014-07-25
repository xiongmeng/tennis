<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('gt_role', function($table)
        {
            $table->increments('id');
            $table->string('role_id');
            $table->string('label');
            $table->string('name');
            $table->string('url');

        });

    }


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('gt_role');
	}

}
