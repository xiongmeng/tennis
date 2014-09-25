<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAccountBalanceNotNull extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `balance` decimal(10,2) DEFAULT '0.00' NOT NULL COMMENT '余额'");
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `freeze` decimal(10,2) DEFAULT '0.00' NOT NULL COMMENT '冻结金额'");
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `credit` decimal(10,2) DEFAULT '0.00' NOT NULL COMMENT '信用额度'");
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `balance` decimal(10,2) DEFAULT '0.00' COMMENT '余额'");
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `freeze` decimal(10,2) DEFAULT '0.00' COMMENT '冻结金额'");
        DB::statement("ALTER TABLE gt_account MODIFY COLUMN `credit` decimal(10,2) DEFAULT '0.00' COMMENT '信用额度'");
	}

}
