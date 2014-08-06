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
}