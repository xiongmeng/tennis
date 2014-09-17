<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForWxlocationCreateUpdateAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('weixin_location', function(Blueprint $table)
    {
        $table->string('created_at');
        $table->string('updated_at');
    });
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    Schema::table('weixin_location', function(Blueprint $table)
    {
        $table->dropColumn('created_at','updated_at');
    });
}

}
