<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstantOrderRefresh extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'instantOrder:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh current instant order.';

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
        $this->info('generate the court for halls who have user but have no court');
        Hall::has('Users')->has('Courts', '=', 0)->chunk(20, function ($halls) {
            $this->info('find halls ' . count($halls));
            $hallIds = array();
            foreach ($halls as $hall) {
                if ($hall instanceof Hall) {
                    $hallIds [] = $hall->id;
                }
            }
            $hallIdString = implode(',', $hallIds);
            $this->info('call court:generate with halls ' . $hallIdString);
            $this->call('court:generate', array('--hall' => $hallIdString));
        });


        $instant = WORKTABLE_SUPPORT_DAYS_LENGTH;
        $dates = array(date('Y-m-d'));
        for ($i = 1; $i < $instant; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i day"));
        }

        foreach ($dates as $date) {
            $this->info('generate instant order for ' . $date);
            Hall::has('Users')->whereHas('InstantOrders', function (Builder $builder) use($date){
                $builder->where('gt_instant_order.event_date', '=', $date);
            }, '=', 0)->chunk(20, function ($halls) use($date){
                    $this->info('find halls ' . count($halls));
                    $hallIds = array();
                    foreach ($halls as $hall) {
                        if ($hall instanceof Hall) {
                            $hallIds [] = $hall->id;
                        }
                    }

                    $hallIdString = implode(',', $hallIds);
                    $this->info('call instantOrder:generate with halls ' . $hallIdString . ' date ' . $date);
                    $this->call('instantOrder:generate', array('--hall' => $hallIdString, '--date' => $date));
                });
        }
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
