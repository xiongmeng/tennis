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
}