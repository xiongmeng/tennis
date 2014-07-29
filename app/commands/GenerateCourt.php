<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCourt extends Command {
    const ARGUMENT_HALL = 'hall';

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
	protected $description = "generate court from table gt_hall_court.";

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
		$hallIds = $this->option(self::ARGUMENT_HALL);

        if(Court::whereIn('hall_id', $hallIds)->exists()){
            $this->error(sprintf("the court has generated for hall_ids(%s)", implode(',', $hallIds)));
            return;
        }

        $halls = Hall::with(array('CourtGroup'))->whereIn('id', $hallIds)->get();

        $batchInserts = array();

        $curTime = date('Y-m-d H:i:s');
        foreach($halls as $hall){
            $hallId = $hall->id;
            $courtNum = $hall->CourtGroup->count;
            $groupId = $hall->CourtGroup->id;
            $this->info(sprintf("generating for hall (%s) with %s court" , $hallId, $courtNum));

            for($number=0; $number < $courtNum; $number++){
                $batchInserts[] = array(
                    'number' => $number,
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
			array(self::ARGUMENT_HALL, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'the hall id', null)
        );
	}

}
