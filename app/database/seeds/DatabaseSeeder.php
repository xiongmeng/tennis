<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        $start = strtotime('2015-04-04');
        for ($i = 0; $i < 30; $i++) {
            $time = strtotime("+$i day", $start);
            $this->call('instantOrder:generate', array('--date' => date('Y-m-d', $time)));

        }
        exit;

	}

}
