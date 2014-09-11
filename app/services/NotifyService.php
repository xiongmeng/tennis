<?php

class NotifyChunkPack{
    public $offset;
    public $isStop;

    public function __construct($offset=0, $isStop=true){
        $this->isStop = $isStop;
        $this->offset = $offset;
    }
}

class NotifyService  {
    private $channels = array();
    private $events = array();

    public function __construct($events, $channels){
        $this->events = $events;
        $this->channels = $channels;
    }

    public function push($eventId, $objectId){

    }

    public function doNotify($eventKey, $objectId){
        if(!isset($this->events[$eventKey])){
            throw new Exception("specified event $eventKey is not existed!");
        }
        $event = $this->events[$eventKey];

        $object = call_user_func($event['object'], $objectId);

        $chunk = new NotifyChunkPack(0, true);
        do{
            $users = call_user_func($event['users'], $object, $chunk);

            $chunk->offset += count($users);

            foreach($users as $user){
                $channels = $this->channels;
                /**
                 * TODO - 查找用户关闭的发送通道
                 */
                foreach($channels as $channel){
                    $msg = call_user_func($event['msg'], $object, $user, $channel);
                    call_user_func($channel['send'], $msg, $user);
                }
            }
        }while(!$chunk->isStop);
    }
}