<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexForReserveOrder extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gt_account_billing_staging', function(Blueprint $table){
            $table->index(array('relation_id', 'relation_type', 'purpose', 'billing_type'),
                'gt_account_billing_staging_main_index');
        });

        Schema::table('gt_order', function(Blueprint $table){
            $table->index(array('stat', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gt_account_billing_staging', function(Blueprint $table){
            $table->dropIndex('gt_account_billing_staging_main_index');
        });

        Schema::table('gt_order', function(Blueprint $table){
            $table->dropIndex('gt_order_stat_user_id');
        });
    }
}
