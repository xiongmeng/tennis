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

    /**
     * @param $seekingOrders array|string|\Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function batchPay($seekingOrders){
        if (!$seekingOrders instanceof \Illuminate\Database\Eloquent\Collection) {
            if(is_string($seekingOrders)){
                $seekingOrders = explode(',', $seekingOrders);
            }

            $countOri = count($seekingOrders);
            $seekingOrders = ReserveOrder::whereIn('id', $seekingOrders)->get();
            if (count($seekingOrders) != $countOri) {
                throw new Exception(sprintf('选取了不存在的场地：选择(%d)，实际(%d)', count($seekingOrders), $countOri));
            }
        }

        foreach ($seekingOrders as $reserve) {
            $this->resetObject($reserve);
            $this->apply('pay_success');
        }
    }
}