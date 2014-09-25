<?php

class ReserveOrderFsm extends \Finite\StateMachine\StateMachine {
    public function __construct(ReserveOrder $reserveOrder = null) {
        parent::__construct($reserveOrder);

        $loader = new Finite\Loader\ArrayLoader(Config::get('fsm.reserve_order'));
        $loader->load($this);

        if($reserveOrder != null){
            $this->initialize();
        }
    }

    public function resetObject(ReserveOrder $instantOrder){
        $this->setObject($instantOrder);
        $this->initialize();
    }

    /**
     * @param $reserves array|string|\Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function batchPay($reserves){
        if (!$reserves instanceof \Illuminate\Database\Eloquent\Collection) {
            if(is_string($reserves)){
                $reserves = explode(',', $reserves);
            }

            $idsCount = count($reserves);
            $reserves = ReserveOrder::whereIn('id', $reserves)->get();
            if (count($reserves) != $idsCount) {
                throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($reserves), count($reserves)));
            }
        }

        foreach ($reserves as $reserve) {
            $this->resetObject($reserve);
            $this->apply('pay_success');
        }
    }
}