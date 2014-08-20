<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class InstantOrderManager {
    /**
     * 加载制定场馆制定日期的工作台信息
     * @param $hallId
     * @param $date
     */
    public function loadWorktableByHallAndDate($hallId, $date){
        $statistics = array();

        $instants = InstantOrder::orderBy('start_hour', 'asc')
            ->where('hall_id', '=', $hallId)->where('event_date', '=', $date)->get();

        $formattedInstants = array();
        foreach($instants as $instant){
            !isset($formattedInstants[$instant->start_hour]) && $formattedInstants[$instant->start_hour] = array();
            $formattedInstants[$instant->start_hour][$instant->court_id] = $instant;

            !isset($statistics[$instant->state]) && $statistics[$instant->state] = 0;
            $statistics[$instant->state]++;
        }
        $statistics['total'] = count($instants);

        $courts = Court::where('hall_id', '=', $hallId)->get();

        $hours = array();
        $startHour = $instants->first() ? $instants->first()->start_hour : 7;
        $endHour = $instants->last() ? $instants->last()->end_hour : 24;
        for($index = $startHour; $index < $endHour;$index++){
            $hours[] = array('start'=>$index + 0 , 'end' => $index + 1);
        }

        $instantOrdersByHours = $hours;
        $states = Config::get('state.data');

        foreach($instantOrdersByHours as &$instantOrdersByHour){
            $start = $instantOrdersByHour['start'];
            foreach($courts as &$court){
                $order = array();
                if(isset($formattedInstants[$start]) && isset($formattedInstants[$start][$court->id])){
                    $order = $formattedInstants[$start][$court->id];
                    $order['state_text'] = $states[$order->state];
                    $order['select'] = false;
                }

                $instantOrdersByHour['instantOrders'][] = $order;
            }
        }

        $loginUser = Auth::getUser();
        $loginUserId = $loginUser ? $loginUser->user_id : '';
        return array('hours' =>$hours, 'courts' => $courts, 'states' => $states, 'statistics' => $statistics,
            'instantOrdersByHours' => $instantOrdersByHours, 'loginUserId' => $loginUserId);
    }
}