<?php
use Sports\Finance\Operate\OperateObject;
use Sports\Constant\Finance as FinanceConstant;
use Sports\Finance\Operate\ActionObject;

class ReserveOrderManager
{
    private $fsm = null;

    public function __construct()
    {
        $this->fsm = new ReserveOrderFsm();
    }

    public function batchPay($reserves, $payUserId = null){
        if (!$reserves instanceof \Illuminate\Database\Eloquent\Collection) {
            $reserves = ReserveOrder::whereIn('id', $reserves)->get();
            if (count($reserves) != count($reserves)) {
                throw new Exception(sprintf('选取了不存在的预约订单：选择(%d)，实际(%d)', count($reserves), count($reserves)));
            }
        }

        //获取总共需要支付的钱数
        $needPay = 0;
        foreach ($reserves as $reserve) {
            $needPay += $reserve->cost;
        }
        $result['needPay'] = $needPay;

        //获取用户当前余额
        if(empty($payUserId)){
            $user = Auth::getUser();
            $payUserId = $user->user_id;
        }

        $account = Finance::ensureAccountExisted($payUserId, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
        $result['balance'] = $account->getAvailableAmount();

        $result['needRecharge'] = $needPay - $result['balance'];

        //如果可用余额不够 进支付宝，否则轮询支付
        if ($result['needRecharge'] > 0) {
            $reserveOrderIds = array();
            foreach($reserves as $reserve){
                $reserveOrderIds[] = $reserve->id;
            }

            //预先生成recharge表
            $recharge = new Recharge();
            $recharge->generate($result['needRecharge'], $payUserId,
                RECHARGE_CALLBACK_PAY_RESERVE_ORDER, implode(',', $reserveOrderIds));

            no_money_generate_url($result, $recharge);

            $result['status'] = 'no_money';
        } else {
            $this->fsm->batchPay($reserves);
            $result['adviseForwardUrl'] = '/instant_order_buyer';

            $result['status'] = 'pay_success';
        }

        return $result;
    }

    /**
     * @param $order array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num'))
     * @return array
     * @throws Exception
     */
    public function calculate(&$order){
        $hallMarkets = HallMarket::with('HallPrice')->whereHallId($order['hall_id'])->get();

        $user = User::findOrFail($order['user_id']);
        adjustTimestampForOneModel($user);

        $eventDate = strtotime(date('Y-m-d', $order['event_date']));
        $holiday = LegalHolidays::whereDate($eventDate)->first();
        $week = intval(date('N', $eventDate));

        //查找指定日期所在的市场
        $dateHitMarkets = array();
        if ($holiday instanceof LegalHolidays) {
            foreach ($hallMarkets as $hallMarket) {
                if ($hallMarket->type == $holiday->type) {
                    $dateHitMarkets[] = $hallMarket;
                }
            }
        } else {
            foreach ($hallMarkets as $hallMarket) {
                if (($hallMarket->start_week <= $week) && ($hallMarket->end_week >= $week)) {
                    $dateHitMarkets[] = $hallMarket;
                }
            }
        }

        //查找指定时间段所在的市场
        $hourHitMarkets = array();
        for ($timeIndex = $order['start_time']; $timeIndex < $order['end_time']; $timeIndex++) {
            $existed = false;
            foreach ($dateHitMarkets as $market) {
                if ($market['start'] <= $timeIndex && $market['end'] >= $timeIndex + 1) {
                    $hourHitMarkets[] = $market;
                    $existed = true;
                }
            }
            if (!$existed) {
                throw new Exception(sprintf("未找见指定的时间段%s-%s", $timeIndex, $timeIndex + 1));
            }
        }

        //计算结果值
        $costs = array('market' => 0, 'member' => 0, 'vip' => 0, 'purchase' => 0);
        $courtNum = $order['court_num'];
        foreach ($hourHitMarkets as $market) {
            $costs['market'] += $market->HallPrice->market * $courtNum;
            $costs['member'] += $market->HallPrice->member * $courtNum;
            $costs['vip'] += $market->HallPrice->vip * $courtNum;
            $costs['purchase'] += $market->HallPrice->purchase * $courtNum;
        }

        //向订单结构中赋值
        $order['cost'] = $user->privilege == PRIVILEGE_GOLD ? $costs['vip'] : $costs['member'];

        return array('week' => $week, 'costs' => $costs, 'hall_markets' => $hallMarkets, 'order' => $order,
            'holiday' => $holiday, 'date_hit_markets' => $dateHitMarkets, 'hour_hit_markets' => $hourHitMarkets);
    }
}