<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstantOrderWaste extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'instantOrder:waste';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'waste instant_order.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $affectedRows = InstantOrder::where('state', '=', 'draft')->where('start_hour', '<=', date('H'))
            ->where('event_date', '<=' , date('Y-m-d'))->update(array('state' => 'waste'));

        $this->info('execute completed with affected rows ' . $affectedRows);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
