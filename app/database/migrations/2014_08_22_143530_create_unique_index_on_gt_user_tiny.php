<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniqueIndexOnGtUserTiny extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE gt_user_tiny MODIFY COLUMN `nickname` char(32) BINARY DEFAULT NULL");
        Schema::table('gt_user_tiny', function(Blueprint $table){
            $table->unique('nickname');
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
            $table->dropUnique('gt_user_tiny_nickname_unique');
        });
        DB::statement("ALTER TABLE gt_user_tiny MODIFY COLUMN `nickname` char(32) DEFAULT NULL");
    }

}
