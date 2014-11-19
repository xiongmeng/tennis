<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeParticipantToJoinerFoSeerkingOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_seeking_order', function(Blueprint $table){
            $table->renameColumn('participant', 'joiner');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gt_seeking_order', function(Blueprint $table){
            $table->renameColumn('joiner', 'participant');
        });
	}

}
