<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpireTimeToSeekingOrder extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gt_seeking_order', function (Blueprint $table) {
            $table->integer('expire_time');
            $table->timestamp('event_date');
            $table->integer('hall_id');
            $table->integer('start_hour');
            $table->integer('end_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gt_seeking_order', function (Blueprint $table) {
            $table->dropColumn('expire_time');
            $table->dropColumn('event_date');
            $table->dropColumn('hall_id');
            $table->dropColumn('start_hour');
            $table->dropColumn('end_hour');
        });
    }

}
