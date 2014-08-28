<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexOnGtInstantOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gt_instant_order', function(Blueprint $table){
            $table->index('state');
            $table->index(array('event_date', 'hall_id'));
            $table->index(array('buyer', 'state'));
            $table->index(array('seller', 'state'));
            $table->index('expire_time');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_instant_order', function(Blueprint $table){
            $table->dropIndex('gt_instant_order_state_index');
            $table->dropIndex('gt_instant_order_event_date_hall_id_index');
            $table->dropIndex('gt_instant_order_buyer_state_index');
            $table->dropIndex('gt_instant_order_seller_state_index');
            $table->dropIndex('gt_instant_order_expire_time_index');
        });
	}

}
