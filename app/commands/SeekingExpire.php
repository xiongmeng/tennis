<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SeekingExpire extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seeking:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'expire seeking.';

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
        //处理约球单得过期事件
        $seekingOrderFsm = new SeekingOrderFsm();
        SeekingOrder::where('expire_time', '>', 0)->where('expire_time', '<', time())
            ->chunk(50, function ($seekingOrderList) use ($seekingOrderFsm) {
                foreach ($seekingOrderList as $seekingOrder) {
                    $seekingOrderFsm->resetObject($seekingOrder);
                    if ($seekingOrderFsm->can('expire')) {
                        $seekingOrderFsm->apply('expire');
                        $this->info($seekingOrder->id, 'expire');
                    } else if ($seekingOrderFsm->can('pay_expire')) {
                        $seekingOrderFsm->apply('pay_expire');
                        $this->info($seekingOrder->id, 'pay_expire');
                    } else {
                        $this->error($seekingOrder->id, 'has no operate');
                    }
                }
            });

        //处理约球的过期操作
        $seekingFsm = new SeekingFsm();
        Seeking::where('expire_time', '>', 0)->where('expire_time', '<', time())
            ->chunk(50, function ($seekingList) use ($seekingFsm) {
                foreach ($seekingList as $seeking) {
                    $seekingFsm->resetObject($seeking);
                    if ($seekingFsm->can('expire')) {
                        $seekingFsm->apply('expire');
                        $this->info($seeking->id, 'expire');
                    } else {
                        $this->error($seeking->id, 'has no operate');
                    }
                }
            });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}
