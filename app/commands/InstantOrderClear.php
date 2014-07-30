<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;

class InstantOrderClear extends Command
{
    const OPTION_HALL = 'hall';
    const OPTION_DATE = 'date';
    const OPTION_COURT = 'court';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'instantOrder:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate instant order.';

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
        $dates = $this->option(self::OPTION_DATE);
        $hallIds = $this->option(self::OPTION_HALL);
        $courtIds = $this->option(self::OPTION_COURT);

        $isExistChanged = InstantOrder::where(function(Builder $builder) use($dates, $hallIds, $courtIds){
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
            if (count($courtIds) > 0) {
                $builder->getQuery()->whereIn('court_id', $courtIds);
            }
            if (count($dates) > 0){
                $builder->getQuery()->whereIn('event_date', $dates);
            }
        })->whereRaw('updated_at > created_at')->count();

        if($isExistChanged){
            $this->error('specified condition has been changed!');
            return;
        }

        $affectedRows = InstantOrder::where(function(Builder $builder) use($dates, $hallIds, $courtIds){
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
            if (count($courtIds) > 0) {
                $builder->getQuery()->whereIn('court_id', $courtIds);
            }
            if (count($dates) > 0){
                $builder->getQuery()->whereIn('event_date', $dates);
            }
        })->delete();

        $this->info('clear completed with affected rows ' . $affectedRows);
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
        return array(
            array(self::OPTION_DATE, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'date with php supported format .', null),
            array(self::OPTION_HALL, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'hall ids.', null),
            array(self::OPTION_COURT, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'court ids.', null),
        );
    }

}
