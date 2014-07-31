<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstantOrderGenerate extends Command
{
    const OPTION_HALL = 'hall';
    const OPTION_DATE = 'date';
    const OPTION_COURT = 'court';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'instantOrder:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear instant order.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function isExist(&$dates, &$hallIds, &$courtIds){
        return InstantOrder::where(function(Builder $builder) use($dates, $hallIds, $courtIds){
            if (count($hallIds) > 0) {
                $builder->getQuery()->whereIn('hall_id', $hallIds);
            }
            if (count($courtIds) > 0) {
                $builder->getQuery()->whereIn('court_id', $courtIds);
            }
            if (count($dates) > 0){
                $builder->getQuery()->whereIn('event_date', $dates);
            }
        })->exists();
    }

    private function formatDateAndCheckIsHoliday(&$dates){
        $dateTimestamps = array();
        foreach ($dates as $date) {
            $formatTimeStamp = strtotime(date('Y-m-d', strtotime($date)));
            $dateTimestamps[$formatTimeStamp] = null;
        }

        if (count($dateTimestamps) < 1) {
            return false;
        }

        $holidays = LegalHolidays::whereIn('date', array_keys($dateTimestamps))->get();
        foreach ($holidays as $holiday) {
            $dateTimestamps[$holiday->date] = $holiday->type;
        }

        return $dateTimestamps;
    }

    private function findMarketForDate(&$hallMarkets, &$dateTimestamps){
        $dateHitHallMarkets = array();
        foreach ($dateTimestamps as $dateTimestamp => $holiday) {
            $iWeek = intval(date('N', $dateTimestamp));
            $hitHallMarket = array();
            if ($holiday instanceof LegalHolidays) {
                foreach ($hallMarkets as $hallMarket) {
                    if ($hallMarket->type == $holiday->type) {
                        $hitHallMarket[] = $hallMarket;
                    }
                }
            } else {
                //否则按照平时计算,查找当前星期所在的market
                foreach ($hallMarkets as $hallMarket) {
                    if (($hallMarket->start_week <= $iWeek) && ($hallMarket->end_week >= $iWeek)) {
                        $hitHallMarket[] = $hallMarket;
                    }
                }
            }

            $dateHitHallMarkets[$dateTimestamp] = $hitHallMarket;
        }

        return $dateHitHallMarkets;
    }

    private function generateInstanceOrderFromDatedHallMarkets($dateHitHallMarkets){
        $instantOrders = array();

        $curTime = date('Y-m-d H:i:s');
        foreach ($dateHitHallMarkets as $dateTimestamp => $hallMarkets) {
            $mysqlDate = date('Y-m-d H:i:s', $dateTimestamp);
            foreach ($hallMarkets as $hallMarket) {
                $generatedPrice = $hallMarket->HallPrice->vip;
                for ($time = $hallMarket->start; $time < $hallMarket->end; $time++) {
                    foreach ($hallMarket->Courts as $court) {
                        $instantOrders[] = array(
                            'created_at' => $curTime,
                            'updated_at' => $curTime,
                            'hall_id' => $hallMarket->hall_id,
                            'court_id' => $court->id,
                            'event_date' => $mysqlDate,
                            'start_hour' => $time,
                            'end_hour' => $time + 1,
                            'seller' => '',
                            'generated_price' => $generatedPrice,
                            'quote_price' => $generatedPrice,
                            'seller_service_fee' => '',
                            'hall_name' => $hallMarket->Hall->name,
                            'court_tags' => $hallMarket->CourtGroup->name,
                        );
                    }
                }
            }
        }

        return $instantOrders;
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


        if($this->isExist($dates, $hallIds, $courtIds)){
            $this->error('specified condition has been generated!');
            return;
        }

        $dateTimestamps = $this->formatDateAndCheckIsHoliday($dates);
        if(!$dateTimestamps){
            $this->error(sprintf('the inputted date(%s) is unusable', implode(',', $dates)));
            return;
        }

        HallMarket::with(
            array('Hall', 'HallPrice', 'CourtGroup', 'Courts' => function (HasMany $builder) use ($courtIds) {
                    if (count($courtIds) > 0) {
                        $builder->getBaseQuery()->whereIn('id', $courtIds);
                    }
                }))
            ->whereHas('Courts', function(Builder $builder) use($hallIds, $courtIds){
                if (count($hallIds) > 0) {
                    $builder->getQuery()->whereIn('gt_court.hall_id', $hallIds);
                }
                if (count($courtIds) > 0) {
                    $builder->getQuery()->whereIn('gt_court.id', $courtIds);
                }
            })
            ->chunk(10, function ($hallMarkets) use ($dateTimestamps) {

                $dateHitHallMarkets = $this->findMarketForDate($hallMarkets, $dateTimestamps);

                $instantOrders = $this->generateInstanceOrderFromDatedHallMarkets($dateHitHallMarkets);

                InstantOrder::insert($instantOrders);
                $this->info('generated records ' . count($instantOrders));
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
            array(self::OPTION_DATE, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'date with php supported format .', null),
            array(self::OPTION_HALL, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'hall ids.', null),
            array(self::OPTION_COURT, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'court ids.', null),
        );
    }

}
