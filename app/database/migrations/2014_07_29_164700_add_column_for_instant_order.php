<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForInstantOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_instant_order', function(Blueprint $table){
            $table->integer('hall_id');
            $table->integer('court_id');
            $table->timestamp('event_date');
            $table->integer('start_hour');
            $table->integer('end_hour');
            $table->integer('buyer')->nullable();
            $table->integer('seller');
            $table->decimal('generated_price');
            $table->decimal('quote_price');
            $table->integer('seller_service_fee');
            $table->string('hall_name');
            $table->string('buyer_name');
            $table->string('court_tags');
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
            $table->dropColumn(array(
                'hall_id',
                'court_id',
                'event_date',
                'start_hour',
                'end_hour',
                'buyer',
                'seller',
                'generated_price',
                'quote_price',
                'seller_service_fee',
                'hall_name',
                'buyer_name',
                'court_tags',
            ));
        });
	}

}
