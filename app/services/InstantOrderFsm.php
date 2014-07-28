<?php

class InstantOrderFsm extends \Finite\StateMachine\StateMachine {
    public function __construct(InstantOrder $instantOrder) {
        parent::__construct($instantOrder);

        $loader = new Finite\Loader\ArrayLoader(Config::get('fsm.instant_order'));
        $loader->load($this);

        $this->initialize();
    }
}