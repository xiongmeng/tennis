<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForRechargeCreateUpdateAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('gt_recharge', function(Blueprint $table)
        {
            $table->string('created_at');
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
        Schema::table('gt_recharge', function(Blueprint $table)
        {
            $table->dropColumn('created_at','updated_at');
        });
    }

}
