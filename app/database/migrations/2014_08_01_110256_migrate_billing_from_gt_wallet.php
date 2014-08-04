<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateBillingFromGtWallet extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::insert('INSERT INTO gt_account_billing(`account_id`,`type`,`action_id`,`account_before`,`account_change`,`account_after`,`relation_id`,`relation_type`,`created_time`)
SELECT a.id, 1, 0, w.account_after-w.`offset`,w.`offset`,w.account_after,w.iToken,w.change_reason,w.change_date FROM gt_wallet w LEFT JOIN gt_account a ON w.user_id=a.user_id AND purpose=1');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete('DELETE FROM gt_account_billing WHERE action_id=0');
	}

}
