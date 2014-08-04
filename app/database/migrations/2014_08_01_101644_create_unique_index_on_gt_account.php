<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniqueIndexOnGtAccount extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gt_account', function(Blueprint $table){
            $table->unique(array('user_id', 'purpose'), 'account_user_id_purpose_unique');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_account', function(Blueprint $table){
            $table->dropUnique('account_user_id_purpose_unique');
        });
	}

}
