<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatRelationUesrAppTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('gt_relation_user_app', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('app_id');
            $table->string('app_user_id');
            $table->timestamps();
        });
        if (Schema::hasTable('gt_relation_user_app')&&Schema::hasTable('gt_user_tiny'))
        {
            DB::insert('insert into `gt_relation_user_app`(`user_id`,`app_id`,`app_user_id`) select `user_id`,2,`weixin_openid` from `gt_user_tiny` where   length (`weixin_openid`) >1 ');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gt_relation_user_app');
    }

}
