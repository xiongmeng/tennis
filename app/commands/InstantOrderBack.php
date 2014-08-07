<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstantOrderBack extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'instantOrder:back';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'back instant order and remove draft instant order.';

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
        $lastDate = date('Y-m-d', strtotime('-1 day'));

        $backUpTableName = 'gt_instant_order_' . date('Y_m_d', strtotime('-1 day'));

        $this->info('create table gt_instant_order to ' . $backUpTableName);
        DB::statement("CREATE TABLE $backUpTableName LIKE gt_instant_order");

        $this->info('insert data to ' . $backUpTableName);
        DB::insert("INSERT INTO $backUpTableName
            SELECT * FROM gt_instant_order WHERE (event_date > '". $lastDate. "') || (state!='draft')");

        $this->info('swap table name');
        Schema::rename('gt_instant_order', 'gt_instant_order_swap');
        Schema::rename($backUpTableName, 'gt_instant_order');
        Schema::rename('gt_instant_order_swap', $backUpTableName);
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
