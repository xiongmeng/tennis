<?php

class ReserveOrder extends Eloquent implements \Finite\StatefulInterface{

    protected $table = 'gt_order';
    protected $fillable = array('user_id', 'hall_id', 'event_date', 'start_time', 'end_time', 'court_num', 'cost');
    /**
     * Sets the object state
     *
     * @return string
     */
    public function getFiniteState(){
        return $this->stat;
    }

    /**
     * Sets the object state`
     *
     * @param string $state
     */
    public function setFiniteState($stat){
        $this->stat = $stat;
        $this->save();
    }

    public function User(){
        return $this->belongsTo('User', 'user_id', 'user_id');
    }

    public function Hall(){
        return $this->belongsTo('Hall', 'hall_id', 'id');
    }

    public function search($aQuery, $iPageSize = 20, $relations = array(), $remember = null){
        is_string($relations) && ($relations = (strlen($relations) > 0) ? explode(',', $relations) : array());

        $query = ReserveOrder::with($relations)->leftJoin('gt_hall_tiny', 'gt_hall_tiny.id', '=', 'gt_order.hall_id')
            ->leftJoin('gt_user_tiny', 'gt_user_tiny.user_id', '=', 'gt_order.user_id');
        if(!empty($aQuery['id'])){
            $query->where('gt_order.id', '=', $aQuery['id']);
        }
        if(!empty($aQuery['hall_name'])){
            $query->where('gt_hall_tiny.name', 'like', '%' . $aQuery['hall_name'] . '%');
        }
        if(!empty($aQuery['event_date_start'])){
            $query->where('gt_order.event_date', '>=', strtotime($aQuery['event_date_start']));
        }
        if(!empty($aQuery['event_date_end'])){
            $query->where('gt_order.event_date', '<=', strtotime($aQuery['event_date_end']));
        }
        if(!empty($aQuery['buyer_name'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['buyer_name'] . '%');
        }
        if(isset($aQuery['stat'])){
            $stat = $aQuery['stat'];
            if(is_array($stat)){
                $query->whereIn('gt_order.stat', $stat);
            }else {
                $query->where('gt_order.stat', '=', $stat);
            }
        }
        if(isset($aQuery['user_id'])){
            $userId = $aQuery['user_id'];
            if(is_array($userId)){
                $query->whereIn('gt_order.user_id', $userId);
            }else {
                $query->where('gt_order.user_id', '=', $userId);
            }
        }
        if(isset($aQuery['stat_ne'])){
            if(is_array($aQuery['stat_ne'])){
                $query->whereNotIn('gt_order.stat', $aQuery['stat_ne']);
            }else{
                $query->where('gt_order.stat', '!=', $aQuery['stat_ne']);
            }
        }
        $remember !== null && $query->remember($remember);

        $reserves = $query->orderBy('gt_order.id', 'desc')
            ->paginate($iPageSize, array('gt_order.*',
                'gt_hall_tiny.name as hall_name', 'gt_user_tiny.nickname as buyer_name'));

        return $reserves;
    }

    public function generate($order){
        !isset($order['stat']) && $order['stat'] = RESERVE_STAT_INIT;
        return ReserveOrder::create($order);
    }
}