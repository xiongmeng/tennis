<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTablesNameFromCourtToName extends Migration {

    private $tables = array(
        'gt_court_active',
        'gt_court_balance',
        'gt_court_billing',
        'gt_court_card',
        'gt_court_court',
        'gt_court_discuss',
        'gt_court_image',
        'gt_court_map',
        'gt_court_market',
        'gt_court_market_week',
        'gt_court_price',
        'gt_court_recharge',
        'gt_court_register',
        'gt_court_tiny',
        'gt_court_type',
    );

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach($this->tables as $courtTable){
            $count = 0;
            $hallTable = str_replace('gt_court', 'gt_hall', $courtTable, $count);
            if($count === 1){
                Schema::rename($courtTable, $hallTable);
            }
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach($this->tables as $courtTable){
            $count = 0;
            $hallTable = str_replace('gt_court', 'gt_hall', $courtTable, $count);
            if($count === 1){
                Schema::rename($hallTable, $courtTable);
            }
        }
	}

}
