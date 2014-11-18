<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeekingOrderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gt_seeking_order', function(Blueprint $table){
            $table->integer('id', true);
            $table->char('state', 16);
            $table->integer('seeking_id');   //约球id
            $table->integer('seeker');    //约球发起人
            $table->integer('participant'); //参加人
            $table->integer('cost');    //费用
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gt_seeking_order');
    }

}
