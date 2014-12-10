<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustCreateAtFromStringToTimestamp extends Migration {

    var $tables = array('gt_user_tiny', 'gt_recharge', 'gt_ali_log', 'weixin_location', 'gt_order');

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach($this->tables as $table){
            Schema::table($table, function(Blueprint $e){
                $e->renameColumn('created_at', 'created_at_string');
                $e->renameColumn('updated_at', 'updated_at_string');
            });
        }

        foreach($this->tables as $table){
            Schema::table($table, function(Blueprint $e){
                $e->timestamps();
            });
        }

        foreach($this->tables as $table){
            DB::statement(sprintf('UPDATE %s SET created_at=created_at_string', $table));
            DB::statement(sprintf('UPDATE %s SET updated_at=updated_at_string', $table));
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
            Schema::table($table, function(Blueprint $e){
                $e->dropTimestamps();
            });
        }

        foreach($this->tables as $table){
            Schema::table($table, function(Blueprint $e){
//                $e->dropTimestamps();

                $e->renameColumn('created_at_string', 'created_at');
                $e->renameColumn('updated_at_string', 'updated_at');
            });
        }
	}

}
