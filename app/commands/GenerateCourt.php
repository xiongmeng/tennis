<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;

class GenerateCourt extends Command
{
    const OPTION_HALL = 'hall';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'court:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "generate court from table gt_hall_court by hall ids.";

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
        $hallIds = $this->option(self::OPTION_HALL);
        $hallIds = explode(',', $hallIds);

        if (Court::where(function (Builder $builder) use ($hallIds) {
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
        })->exists()
        ) {
            $this->error(sprintf("the court has generated for hall_ids(%s)", implode(',', $hallIds)));
            return;
        }

        Hall::with(array('CourtGroup'))->where(function (Builder $builder) use ($hallIds) {
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('id', $hallIds);
            }
        })->has('CourtGroup')
            ->chunk(100, function ($halls) {
                $batchInserts = array();

                $curTime = date('Y-m-d H:i:s');
                foreach ($halls as $hall) {
                    $hallId = $hall->id;
                    $courtNum = $hall->CourtGroup->count;
                    $groupId = $hall->CourtGroup->id;
                    $this->info(sprintf("generating for hall (%s) with %s court", $hallId, $courtNum));

                    $courtBaseId = $hallId << 8;
                    for ($number = 0; $number < $courtNum; $number++) {
                        $batchInserts[] = array(
                            'id' => $courtBaseId + $number + 1,
                            'number' => $number + 1,
                            'hall_id' => $hallId,
                            'group_id' => $groupId,
                            'created_at' => $curTime,
                            'updated_at' => $curTime,
                        );
                    }
                }

                $this->info("creating begin, court's num is " . count($batchInserts));
                $success = Court::insert($batchInserts);
                $this->info('creating end');
                $this->info($success ? 'success' : 'fail');

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
        return array(
            array(self::OPTION_HALL, null, InputOption::VALUE_OPTIONAL, "the hall ids, contact with ',' [8888,8889]", null)
        );
    }

}
