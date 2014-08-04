<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingStagingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('gt_account_billing_staging', function(Blueprint $table){

            //billing表info
            $table->integer('id');
            $table->integer('account_id');
            $table->integer('billing_type');
            $table->decimal('account_change');
            $table->decimal('account_after');
            $table->integer('relation_id');
            $table->integer('relation_type');
            $table->integer('billing_created_time');

            //gt_account
            $table->integer('user_id');
            $table->integer('purpose');

            //gt_user_tiny
            $table->string('user_name');
            $table->string('user_telephone');
            $table->string('user_realname');

            //gt_recharge
            $table->integer('recharge_type');
            $table->string('recharge_token');

            //gt_order
            $table->integer('booking_event_date');
            $table->integer('booking_start_time');
            $table->integer('booking_end_time');
            $table->integer('hall_id');
            $table->integer('booking_court_num');
            $table->integer('booking_cost');
            $table->string('booking_cost_text');
            $table->string('booking_stat');
            $table->string('booking_created_time');

            //gt_hall
            $table->string('hall_name');

            //gt_finance_custom
            $table->string('finance_custom_reason', 1024);

            $table->primary('id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gt_account_billing_staging');
	}

}
