<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiveSmsTelephoneToUserForHall extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_user_tiny', function(Blueprint $table){
           $table->string('receive_sms_telephone', 1024);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_user_tiny', function(Blueprint $table){
            $table->dropColumn('receive_sms_telephone');
        });
	}

}
