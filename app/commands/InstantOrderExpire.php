<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstantOrderExpire extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'instantOrder:expire';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'expire instant_order.';

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
		$instantOrderModel = new InstantOrder();

        $instants = $instantOrderModel::where('expire_time','>',0)->where('expire_time','<',time())->get();

        $fsm = new InstantOrderFsm();
        foreach($instants as $instant){
            $fsm->resetObject($instant);

            if($fsm->can('expire')){
                $fsm->apply('expire');
                $this->info($instant->id,'expire');
            }
            elseif($fsm->can('pay_expire')){
                $fsm->apply('pay_expire');
                $this->info($instant->id,'pay_expire');
            }
            elseif($fsm->can('event_start')){
                $fsm->apply('event_start');
                $this->info($instant->id,'event_start');
            }
            elseif($fsm->can('event_end')){
                $fsm->apply('event_end');
                $this->info($instant->id,'event_end');
            }
            elseif($fsm->can('confirm_expire')){
                $fsm->apply('confirm_expire');
                $this->info($instant->id,'confirm_expire');
            }
            else{
                $this->error($instant->id,'has no operate');
            }


        }
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
