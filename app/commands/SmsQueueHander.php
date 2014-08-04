<?php

use Illuminate\Console\Command;
use \Sports\Constant\Sms;

class SmsQueueHander extends Command
{
    const ARGUMENT_OPERATE = 'operate';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sms:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "sms queue's operate for console.";

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
        $argument = $this->argument(self::ARGUMENT_OPERATE);

        switch($argument){
            case 'send':
                $queueService = new \Sports\Sms\QueueService(\Sports\Utility\DBHelper::masterAdapterFromLaravel());
                $result = $queueService->sendLoop(10);
                $this->info('send completed');
                $this->info(print_r($result, true));
                break;
            case 'list':
                $statusTexts = array(
                    Sms::QUEUE_STATUS_PENDING => 'pending',
                    Sms::QUEUE_STATUS_SENDING => 'sending',
                    Sms::QUEUE_STATUS_SUSPENDED => 'suspended',
                    Sms::QUEUE_STATUS_COMPLETED => 'completed'
                );
                $statusCounts = SmsQueue::groupBy('status')->get(array('status', DB::raw('count(1) as count')));
                foreach($statusCounts as $statusCount){
                    $this->info(sprintf("%s's count is %s", $statusTexts[$statusCount->status], $statusCount->count));
                }
                break;
            default :
                break;
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
            array(self::ARGUMENT_OPERATE, \Symfony\Component\Console\Input\InputArgument::REQUIRED,
                sprintf(
                    "%s\n%s",
                    "send - merge the new billing info to staging table",
                    "list - list the count of each status"
                ))
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
