<?php

class HallService
{
    public function __construct(){
    }

    public function formatDateAndCheckIsHoliday(&$dates){
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
            $dateTimestamps[$holiday->date] = $holiday;
        }

        return $dateTimestamps;
    }

    /**
     * @param HallMarket[] $hallMarkets
     * @param $dateTimestamps
     * @return array
     */
    public function findMarketForDate(&$hallMarkets, &$dateTimestamps){
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
}