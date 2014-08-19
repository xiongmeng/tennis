<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRechargeCallbackActionIdToVarchar extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_recharge', function(Blueprint $table){
            $table->string('callback_action_token', 1024);
            $table->dropColumn('callback_action_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_recharge', function(Blueprint $table){
            $table->dropColumn('callback_action_token');
            $table->integer('callback_action_id');
        });
	}

}
