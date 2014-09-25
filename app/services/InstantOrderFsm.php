<?php

class InstantOrderFsm extends \Finite\StateMachine\StateMachine {
    public function __construct(InstantOrder $instantOrder = null) {
        parent::__construct($instantOrder);

        $loader = new Finite\Loader\ArrayLoader(Config::get('fsm.instant_order'));
        $loader->load($this);

        if($instantOrder != null){
            $this->initialize();
        }
    }

    public function resetObject(InstantOrder $instantOrder){
        $this->setObject($instantOrder);
        $this->initialize();
    }

    /**
     * @param $instants array|string|\Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function batchPay($instants){
        if (!$instants instanceof \Illuminate\Database\Eloquent\Collection) {
            if(is_string($instants)){
                $instants = explode(',', $instants);
            }

            $idsCount = count($instants);
            $instants = InstantOrder::whereIn('id', $instants)->get();
            if (count($instants) != $idsCount) {
                throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($instants), count($instants)));
            }
        }

        foreach ($instants as $instant) {
            $this->resetObject($instant);
            $this->apply('pay_success');
        }
    }
}