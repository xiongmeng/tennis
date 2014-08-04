<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserAccountRelationToOneToMore extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('gt_account', function(Blueprint $table){
            $table->integer('user_id');
            $table->integer('purpose');
        });

        DB::update('UPDATE gt_account a LEFT JOIN gt_relation_account_user r ON a.id = r.account_id AND r.purpose=2 SET a.purpose=r.purpose , a.user_id=r.user_id');

        DB::insert('INSERT INTO gt_account(`balance`, `created_time`, `user_id`, `purpose`) SELECT prestore_fee,createtime,user_id,1 FROM gt_user_tiny');

        Schema::table('gt_user_tiny', function(Blueprint $table){
            $table->renameColumn('prestore_fee', 'balance_un_support');
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
            $table->renameColumn('balance_un_support', 'prestore_fee');
        });

        DB::delete('DELETE FROM gt_account WHERE purpose=1');

        Schema::table('gt_account', function(Blueprint $table)
        {
            $table->dropColumn('user_id','purpose');
        });
    }

}
