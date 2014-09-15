<?php

class WeiXinController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //生成自定义菜单
        $menu = Config::get('/packages/cooper/wechat/menu.WeChatMenu');
        $client = new \Cooper\Wechat\WeChatClient();
        //Log::info($menu[0]);
        $client->setMenu($menu[0]);
//        $client->deleteMenu();

        //获取微信消息
        $server = new \Cooper\Wechat\WeChatServer();
        $message = $server->getMessage();

        //$this->log($message);
        $appUserID = $message['from'];
        $type = $message['type'];
        $currentdomain = $_SERVER['HTTP_HOST']; //获取当前域名
        $reg_url = "http://" . $currentdomain . "/register?app_user_id=" . $appUserID. '&app_id=2';
        $bond_url = "http://" . $currentdomain . "/bond?app_user_id=" . $appUserID . '&app_id=2';


        /**
         *自定义菜单下
         */
        if ($type === 'event') { //点击菜单事件
            //$weixin->save_log($postStr);//记日志
            $Event = $message['event']; //获取事件类型

            if ($Event == 'subscribe') { //关注事件返回消息
                $reply = $server->getXml4Txt('欢迎关注网球通！');
                echo $reply;
            }

            if ($Event == 'location') { //地理位置事件
                //$weixin->saveLocation($postStr);//存储到数据库
                $this->saveLocation($message);

            }
            if ($Event == 'scan'){
                $reply = $server->getXml4Txt('http://www.gotennis.cn/reserve/recommend?app_id=2&app_user_id='.$appUserID);
                echo $reply;
            }

            if ($Event == 'click') { //CLICK事件

               // $key = $message['key']; //获取当前菜单key
                $isBond = $this->getUser($appUserID);
                $res = $this->WeChatMsg($appUserID,$isBond);
                    $reply = $server->getXml4RichMsgByArray($res);
                    echo $reply;



            }
        }


        if ($type === 'text') { //文本输入
            $content = strtolower($message['content']);

            if ($content == 'jcbd') {
                $isBond = $this->getUser($appUserID);
                if ($isBond) {
                    if ($isBond instanceof RelationUserApp) {
                        $isBond->delete();


                        $reply = $server->getXml4Txt("成功解除绑定");
                    }
                } else {
                    $reply = $server->getXml4Txt("您还没有绑定网球通账号");
                }
            } else {
//                $reply = $server->getXml4Txt("欢迎关注网球通！我们将竭诚为你提供更方便，更低价格的的网球订场服务。");
                $reply = $server->getXml4Txt('http://www.gotennis.cn/mobile_home/reserve/recommend?app_id=2&app_user_id='.$appUserID);

            }

            echo $reply;
        }

    }


    /**
     * return userID by appUserID
     *
     * @return userID
     */
    public function getUser($appUserID)
    {
        try {
            $app = RelationUserApp::where('app_user_id', '=', $appUserID)->first();

        } catch (Exception $e) {
            return false;
        }
        return $app;

    }

    function WeChatMsg($appUserID,$isBond){
        if($isBond){
            return array(
                'WeChatMsg'=> array(
                    0 => array(
                        'title' => '订场就找网球通',
                        'desc' => '订场就找网球通',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cnbond?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    1 => array(
                        'title' => '预约订场',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    2 => array(
                        'title' => '即时订场',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    3 => array(
                        'title' => '活动资讯',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    4 => array(
                        'title' => '个人中心',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                )
            );
        }
        else{
            return array(
                'WeChatMsg'=> array(
                    0 => array(
                        'title' => '订场就找网球通',
                        'desc' => '订场就找网球通',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cnbond?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    1 => array(
                        'title' => '预约订场',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    2 => array(
                        'title' => '即时订场',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    3 => array(
                        'title' => '活动资讯',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    4 => array(
                        'title' => '绑定用户',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                    5 => array(
                        'title' => '注册用户',
                        'desc' => '预定场地1',
                        'pic' => "http://www.wangqiuer.com/assets/img/logo.jpg",
                        'url' => "http://www.gotennis.cn/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                    ),
                )
            );
        }


    }


    /**
     * save Log to SQL
     *
     * @return
     */
    public function log($message)
    {
        $aLocation = new WXLog;
        $aLocation->openid = $message['from'];
        $aLocation->creattime = $message['time'];
        $aLocation->event = $message['event'];
        $aLocation->msgtype = $message['type'];
        $aLocation->save();

    }

    /**
     * save Location  to SQL
     *
     * @return
     */
    public function saveLocation($message)
    {
        $aLocation = new WXLocation;
        $aLocation->openid = $message['from'];
        $aLocation->lat = $message['la'];
        $aLocation->lon = $message['lo'];
        $aLocation->event = $message['event'];
        $aLocation->msgtype = $message['type'];
        $aLocation->save();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
