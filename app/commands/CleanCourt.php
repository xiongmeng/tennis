<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;

class CleanCourt extends Command
{

    const ARGUMENT_HALL = 'hall';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'court:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "clean court by hall ids.";

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

        $isExistChanged = Court::where(function (Builder $builder) use ($hallIds) {
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
        })->whereRaw('updated_at > created_at')->exists();

        if ($isExistChanged) {
            $this->error('specified condition has been changed!');
            return;
        }

        $res = Court::where(function (Builder $builder) use ($hallIds) {
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
        })->delete();

        $this->info('clean over with affected rows ' . $res);
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
            array(self::ARGUMENT_HALL, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'the hall ids', null),
        );
    }

}
