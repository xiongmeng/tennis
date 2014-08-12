<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostPriceForInstantOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_instant_order', function(Blueprint $table){
            $table->decimal('cost_price');//成本价
            $table->string('court_number');//场地号
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
            $table->dropColumn('cost_price', 'court_number');//成本价
        });
	}

}
