<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAccountInstantOrderToInnoDB extends Migration {

    private $tables = array(
        'gt_account',
        'gt_account_billing',
        'gt_instant_order',
        'gt_finance_operate',
        'gt_finance_operate_action'
    );

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach($this->tables as $table){
            DB::statement("ALTER TABLE $table engine=InnoDB");
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach($this->tables as $table){
            DB::statement("ALTER TABLE $table engine=MyISAM");
        }
	}

}
