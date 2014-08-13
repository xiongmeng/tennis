<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCallbackActionIdTypeForRecharge extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('gt_recharge', function(Blueprint $table)
        {
            $table->integer('callback_action_id');
            $table->string('callback_action_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gt_recharge', function(Blueprint $table)
        {
            $table->dropColumn('callback_action_id','callback_action_type');
        });
    }

}
