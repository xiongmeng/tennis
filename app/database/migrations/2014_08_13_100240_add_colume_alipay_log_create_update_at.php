<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumeAlipayLogCreateUpdateAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('gt_ali_log', function(Blueprint $table)
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
        Schema::table('gt_ali_log', function(Blueprint $table)
        {
            $table->dropColumn('created_at','updated_at');
        });
    }

}
