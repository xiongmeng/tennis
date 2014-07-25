<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationUserRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('gt_relation_user_role', function($table)
        {
            $table->increments('id');
            $table->string('user_id');
            $table->string('role_id');

        });
        if (Schema::hasTable('gt_relation_user_role')&&Schema::hasTable('gt_user_tiny'))
        {
            DB::insert('insert into `gt_relation_user_role`(`user_id`,`role_id`) select `user_id`,1 from `gt_user_tiny`');
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('gt_relation_user_role');
	}

}
