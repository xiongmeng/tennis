<?php
class NotifyService  {
    private $channels = array();
    private $events = array();

    public function __construct($events, $channels){
        $this->events = $events;
        $this->channels = $channels;
    }

    /**
     * @param $eventKey
     * @param $objectId
     * @param $channel
     * @return array
     * @throws Exception
     */
    public function getRecord($eventKey, $objectId, $channel){
        if(!isset($this->events[$eventKey])){
            throw new Exception("specified event $eventKey is not existed!");
        }
        $event = $this->events[$eventKey];

        //生成内容和who
        $msg = call_user_func($event['msg'], $objectId, $channel);
        $who = call_user_func($event['who'], $objectId, $channel);

        return array('event' => $eventKey,
                    'object' => $objectId, 'channel' => $channel, 'who' => $who, 'msg' => $msg);
    }

    /**
     * @param $records
     * @return bool
     */
    private function sendRecords(&$records){
        foreach($records as &$record){
            $record['result'] = '' . $this->sendByChannel($record['channel'], $record['who'], $record['msg']);
            $record['created_at'] = new \Carbon\Carbon();
        }

        return NotifyRecord::insert($records);
    }

    /**
     * @param $eventKey
     * @param $objectId
     * @param null $msg
     * @return array
     * @throws Exception
     */
    public function sendWithBusiness($eventKey, $objectId, $msg = null, $channelKeys = null){
        if(!isset($this->events[$eventKey])){
            throw new Exception("specified event $eventKey is not existed!");
        }
        $event = $this->events[$eventKey];

        //默认为所有渠道
        $supportChannelKeys = array_keys($this->channels);
        $channelKeys === null && ($channelKeys = isset($event['channels']) ? $event['channels'] : $supportChannelKeys);
        //确保渠道key是支持的
        $channelKeys = array_intersect($channelKeys, $supportChannelKeys);

        $records = array();
        foreach($channelKeys as $channelKey){
            $record = $this->getRecord($eventKey, $objectId, $channelKey);
            $msg !== null && $record['msg'] = $msg;

            $records[$channelKey] = $record;
        }

        $this->sendRecords($records);

        return $records;
    }

    /**
     * 通过指定渠道发送通知
     * @param $channel  - see constant.php , NOTIFY_CHANNEL_XXXXXXX
     * @param $who  - sms=> telephone, wx => openid
     * @param $msg
     * @return mixed
     * @throws Exception
     */
    public function sendByChannel($channel, $who, $msg){
        if(!array_key_exists($channel, $this->channels)){
            throw new Exception("specified channel $channel is not supported");
        }

        if(App::environment('local')){
            return "don't send because environment is local";
        }

        if($who && $msg){
            return call_user_func($this->channels[$channel]['send'], $msg, $who);
        }
        return "send failed because who or msg is null";
    }
}