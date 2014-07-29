<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnFromCourtToHall extends Migration {

    private $tables = array(
        'gt_hall_active',
        'gt_hall_balance',
        'gt_hall_billing',
        'gt_hall_card',
        'gt_hall_court',
        'gt_hall_discuss',
        'gt_hall_image',
        'gt_hall_map',
        'gt_hall_market',
        'gt_hall_market_week',
        'gt_hall_price',
        'gt_hall_recharge',
        'gt_hall_register',
        'gt_hall_tiny',
        'gt_hall_type',
        'gt_order'
    );

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach($this->tables as $tableName){
            if(Schema::hasColumn($tableName, 'court_id')){
                Schema::table($tableName, function(Blueprint $table){
                    $table->renameColumn('court_id', 'hall_id');
                });
            }else{
                echo sprintf("%s does not have column court_id\n", $tableName);
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
        foreach($this->tables as $tableName){
            if(Schema::hasColumn($tableName, 'hall_id')){
                Schema::table($tableName, function(Blueprint $table){
                    $table->renameColumn('hall_id', 'court_id');
                });
            }else{
                echo sprintf("%s does not have column hall_id\n", $tableName);
            }
        }
	}

}
