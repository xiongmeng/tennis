<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillInitPasswordForHallUser extends Migration
{

    private $keyValue = array(
        'qiushi' => '64398198',
        'ygl' => '64683311',
        'ldzy' => '84369855',
        'klgy' => '64791117',
        'lining' => '56421255',
        'akwd' => '18911608263',
        'lsba' => '57113502',
        'dongba' => '55629988',
        'jinying' => '84318217',
        'gjwqzx' => '84370880',
        'aoti' => '84375608',
        'wjgjwq' => '64795711',
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->keyValue as $nickname => $initPassword) {
            $res = User::whereNickname($nickname)->update(array('init_password' => $initPassword));
            echo sprintf("fill init_password(%s) for %s with res %s\n", $initPassword, $nickname, $res);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
