<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForRechargeAppuserid extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('gt_recharge', function(Blueprint $table)
        {
            $table->integer('app_id');
            $table->string('app_user_id');
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
            $table->dropColumn('app_id','app_user_id');
        });
    }

}
