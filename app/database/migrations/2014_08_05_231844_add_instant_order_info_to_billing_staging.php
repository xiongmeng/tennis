<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstantOrderInfoToBillingStaging extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_account_billing_staging', function(Blueprint $table){
            $table->integer('instant_order_court_id');
            $table->string('instant_order_court_number');
            $table->decimal('instant_order_quote_price');
            $table->integer('instant_order_state');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_account_billing_staging', function(Blueprint $table){
            $table->dropColumn(array('instant_order_court_id',
                'instant_order_court_number', 'instant_order_quote_price', 'instant_order_state'));
        });
	}

}
