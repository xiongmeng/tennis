<?php

class SeekingFsm extends \Finite\StateMachine\StateMachine {
    /**
     * @var Seeking
     */
    protected $object ;

    public function __construct(Seeking $seeking = null) {
        parent::__construct($seeking);

        $loader = new Finite\Loader\ArrayLoader(Config::get('fsm.seeking'));
        $loader->load($this);

        if($seeking != null){
            $this->initialize();
        }
    }

    public function resetObject(Seeking $seeking){
        $this->setObject($seeking);
        $this->initialize();
    }

    public function increase($num){
        $this->object->store += $num;
        $this->object->on_sale += $num;
        $this->apply('increase');
        return $this->object;
    }

    public function decrease($num){
        $this->object->store -= $num;
        $this->object->on_sale -= $num;
        $this->apply('decrease');
        return $this->object;
    }

    public function join($num){
        $this->object->on_sale -= $num;
        $this->apply('join');
        return $this->object;
    }
}