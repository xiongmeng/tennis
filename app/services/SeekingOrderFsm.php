<?php

class SeekingOrderFsm extends \Finite\StateMachine\StateMachine {
    /**
     * @var SeekingOrder
     */
    protected $object ;

    public function __construct(SeekingOrder $seeking = null) {
        parent::__construct($seeking);

        $loader = new Finite\Loader\ArrayLoader(Config::get('fsm.seeking_order'));
        $loader->load($this);

        if($seeking != null){
            $this->initialize();
        }
    }

    public function resetObject(SeekingOrder $seeking){
        $this->setObject($seeking);
        $this->initialize();
    }
}