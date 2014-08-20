<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class InstantOrderManager
{
    private $fsm = null;

    public function __construct()
    {
        $this->fsm = new InstantOrderFsm();
    }

    /**
     * 加载制定场馆制定日期的工作台信息
     * @param $hallId
     * @param $date
     */
    public function loadWorktableByHallAndDate($hallId, $date)
    {
        $statistics = array();

        $instants = InstantOrder::orderBy('start_hour', 'asc')
            ->where('hall_id', '=', $hallId)->where('event_date', '=', $date)->get();

        $formattedInstants = array();
        foreach ($instants as $instant) {
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
        for ($index = $startHour; $index < $endHour; $index++) {
            $hours[] = array('start' => $index + 0, 'end' => $index + 1);
        }

        $instantOrdersByHours = $hours;
        $states = Config::get('state.data');

        foreach ($instantOrdersByHours as &$instantOrdersByHour) {
            $start = $instantOrdersByHour['start'];
            foreach ($courts as &$court) {
                $order = array();
                if (isset($formattedInstants[$start]) && isset($formattedInstants[$start][$court->id])) {
                    $order = $formattedInstants[$start][$court->id];
                    $order['state_text'] = $states[$order->state];
                    $order['select'] = false;
                }

                $instantOrdersByHour['instantOrders'][] = $order;
            }
        }

        $loginUser = Auth::getUser();
        $loginUserId = $loginUser ? $loginUser->user_id : '';
        return array('hours' => $hours, 'courts' => $courts, 'states' => $states, 'statistics' => $statistics,
            'instantOrdersByHours' => $instantOrdersByHours, 'loginUserId' => $loginUserId);
    }

    /**
     * @param $instants  \Illuminate\Database\Eloquent\Collection|[ids]
     * @return mixed
     * @throws Exception
     */
    public function batchBuy($instants)
    {
        if (!$instants instanceof \Illuminate\Database\Eloquent\Collection) {
            $instants = InstantOrder::whereIn('id', $instants)->get();
            if (count($instants) != count($instants)) {
                throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($instants), count($instants)));
            }
        }

        //跳转到--正在支付中
        foreach($instants as $instant){
            $this->fsm->resetObject($instant);
            $this->fsm->apply('buy');
        }

        return $this->batchPay($instants);
    }

    /**
     * @param $instants  \Illuminate\Database\Eloquent\Collection|[ids]
     * @return mixed
     * @throws Exception
     */
    public function batchPay($instants)
    {
        if (!$instants instanceof \Illuminate\Database\Eloquent\Collection) {
            $instants = InstantOrder::whereIn('id', $instants)->get();
            if (count($instants) != count($instants)) {
                throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($instants), count($instants)));
            }
        }

        //获取总共需要支付的钱数
        $needPay = 0;
        foreach ($instants as $instant) {
            $needPay += $instant->quote_price;
        }
        $result['needPay'] = $needPay;

        //获取用户当前余额
        $user = Auth::getUser();
        $account = Finance::getUserAccount($user->user_id, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $result['balance'] = $account->getAvailableAmount();

        $result['needRecharge'] = $needPay - $result['balance'];

        //如果可用余额不够 进支付宝，否则轮询支付
        if ($result['needRecharge'] > 0) {
            $instantOrderIds = array();
            foreach($instants as $instant){
                $instantOrderIds[] = $instant->id;
            }

            $result['adviseForwardUrl'] = sprintf('/recharge/alipay/%s/%s/%s',
                $result['needRecharge'], RECHARGE_CALLBACK_PAY_INSTANT_ORDER, implode(',', $instantOrderIds));

            $result['status'] = 'no_money';
        } else {
            foreach ($instants as $instant) {
                $this->fsm->resetObject($instant);
                $this->fsm->apply('pay_success');
            }

            $result['adviseForwardUrl'] = '/instant_order_buyer';

            $result['status'] = 'pay_success';
        }

        return $result;
    }
}