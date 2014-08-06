<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;

class AccountBillingStaging extends Command
{
    const ARGUMENT_OPERATE = 'operate';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bill:stage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "control the billing staging.";

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
            case 'merge':
                $maxStagingId = DB::table('gt_account_billing_staging')->max('id');
                $maxBillingId = DB::table('gt_account_billing')->max('id');
                $this->info(sprintf('staging max id:%d, billing max id:%d', $maxStagingId, $maxBillingId));
                if($maxStagingId < $maxBillingId){
                    $this->migrate($maxStagingId);
                }
                break;
            case 'refresh':
                $this->info('truncate staging');
                DB::table('gt_account_billing_staging')->truncate();
                $this->migrate();
                break;
            case 'clean':
                $this->info('truncate staging');
                DB::table('gt_account_billing_staging')->truncate();
                break;
            default :
                break;
        }
    }

    private function migrate($maxBillingId = 0){
        $where = $maxBillingId <= 0 ? '' : ' WHERE b.id>'. $maxBillingId;

        $this->info('copy common info to staging');
        DB::insert('
            INSERT INTO gt_account_billing_staging(
            `id`, `account_id`, `billing_type`, `account_change`, `account_after`, `relation_id`, `relation_type`, `billing_created_time`,
            `user_id`, `purpose`,
            `user_name`, `user_telephone`, `user_realname`)
            SELECT
            b.id,b.account_id,b.type,b.account_change,b.account_after,b.relation_id,b.relation_type,b.created_time,
            a.user_id,a.purpose,
            u.nickname,u.telephone,u.realname
            FROM gt_account_billing b
            LEFT JOIN gt_account a ON b.account_id=a.id
            LEFT JOIN gt_user_tiny u ON a.user_id=u.user_id' . $where);

        $this->info('update recharge info');
        DB::update('
            UPDATE gt_account_billing_staging b INNER JOIN gt_recharge r ON b.relation_id=r.id AND b.relation_type=5
            SET b.recharge_type=r.type, b.recharge_token=r.sToken' . $where);

        $this->info('update booking info');
        DB::update('
            UPDATE gt_account_billing_staging b INNER JOIN gt_order o ON b.relation_id=o.id AND b.relation_type IN(1,3,4,6) INNER JOIN gt_hall_tiny h ON o.hall_id=h.id
            SET b.booking_event_date=o.event_date, b.booking_start_time=o.start_time, b.booking_end_time=o.end_time, b.hall_id=o.hall_id, b.booking_court_num=o.court_num,
            b.booking_cost=o.cost, b.booking_cost_text=o.cost_text, b.booking_stat=o.stat, b.booking_created_time=o.createtime, b.hall_name=h.`name`' . $where);

        $this->info('update instant_order info');
        DB::update('
            UPDATE gt_account_billing_staging b INNER JOIN gt_instant_order o ON b.relation_id=o.id AND b.relation_type IN(12,13,14) INNER JOIN gt_hall_tiny h ON o.hall_id=h.id INNER JOIN gt_court c ON o.court_id=c.id
            SET b.booking_event_date=UNIX_TIMESTAMP(o.event_date), b.booking_start_time=o.start_hour, b.booking_end_time=o.end_hour,
            b.instant_order_court_id=c.id, b.instant_order_court_number=c.number, b.instant_order_quote_price=o.quote_price, b.instant_order_state=o.state,
            b.hall_id=o.hall_id, b.hall_name=h.`name`' . $where);

        $this->info('update custom finance info');
         DB::update('
            UPDATE gt_account_billing_staging b INNER JOIN gt_finance_custom c ON b.relation_id=c.id AND b.relation_type IN (10,11)
             SET b.finance_custom_reason=c.reason' . $where);

         $this->info('migrate completed');
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
                    "%s\n%s\n%s",
                    "merge - merge the new billing info to staging table",
                    "refresh - refresh all the billing info to staging table",
                    "clean - clearing the staging table"
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
