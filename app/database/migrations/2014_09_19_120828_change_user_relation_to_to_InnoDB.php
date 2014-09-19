<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserRelationToToInnoDB extends Migration {

    private $tables = array(
        'gt_user_tiny',
        'gt_relation_user_app',
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach($this->tables as $table){
            DB::statement("ALTER TABLE $table engine=InnoDB");
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
            DB::statement("ALTER TABLE $table engine=MyISAM");
        }
    }

}
