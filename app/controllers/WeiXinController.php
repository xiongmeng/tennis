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
        //获取微信消息
        $server = new \Cooper\Wechat\WeChatServer();
        $message = $server->getMessage();

        $appUserID = $message['from'];
        $type = $message['type'];

        /**
         *自定义菜单下
         */
        if ($type === 'event') { //点击菜单事件
            $Event = $message['event']; //获取事件类型

            if ($Event == 'subscribe') { //关注事件返回消息
                $reply = $server->getXml4Txt('欢迎关注网球通！我们将竭诚为你提供更方便，更低价格的的网球订场服务。更多内容请点击菜单项！');
                echo $reply;
            }

            if ($Event == 'location') { //地理位置事件
                $this->saveLocation($message);

            }
            if ($Event == 'scan') {
                $reply = $server->getXml4Txt('http://www.gotennis.cn/reserve/recommend?app_id=2&app_user_id=' . $appUserID);
                echo $reply;
            }

            if ($Event == 'click') { //CLICK事件
                echo $server->getXml4Txt('精彩内容，敬请期待.......');
            }
        }


        if ($type === 'text') { //文本输入
            $content = strtolower($message['content']);
            $host = "http://" . $_ENV['DOMAIN_WE_CHAT'];
            switch($content){
                case 'jcbd':
                    echo $server->getXml4Txt("$host/jcbd");
                    break;
                case 'logout':
                    echo $server->getXml4Txt("$host/logout");
                    break;
                case 'register':
                case '注册':
                    echo $server->getXml4Txt("$host/mobile_register");
                    break;
                default:
                    echo $server->getXml4Txt('欢迎关注网球通！我们将竭诚为你提供更方便，更低价格的的网球订场服务。更多内容请点击菜单项！');
                    break;
            }
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

    function WeChatMsg($appUserID, $isBond)
    {
        $domain = $_ENV['DOMAIN_MOBILE'];
        if ($isBond) {
            return array(

                0 => array(
                    'title' => '订场就找网球通',
                    'desc' => '订场就找网球通',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/logo.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                ),
                1 => array(
                    'title' => '预约订场',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                ),
                2 => array(
                    'title' => '即时订场',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/instant?app_user_id=" . $appUserID . '&app_id=2'
                ),
                3 => array(
                    'title' => '活动资讯',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve?app_user_id=" . $appUserID . '&app_id=2'
                ),
                4 => array(
                    'title' => '个人中心',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_buyer?app_user_id=" . $appUserID . '&app_id=2'
                ),

            );
        } else {
            return array(

                0 => array(
                    'title' => '订场就找网球通',
                    'desc' => '订场就找网球通',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/logo.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                ),
                1 => array(
                    'title' => '预约订场',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                ),
                2 => array(
                    'title' => '即时订场',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/instant?app_user_id=" . $appUserID . '&app_id=2'
                ),
                3 => array(
                    'title' => '活动资讯',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_home/reserve/recommend?app_user_id=" . $appUserID . '&app_id=2'
                ),
                4 => array(
                    'title' => '绑定用户',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/bond.jpg",
                    'url' => "http://" . $domain . "/mobile_bond?app_user_id=" . $appUserID . '&app_id=2'
                ),
                5 => array(
                    'title' => '注册用户',
                    'desc' => '预定场地1',
                    'pic' => "http://www.wangqiuer.com/Images/weixinImage/TopPic/ibill.jpg",
                    'url' => "http://" . $domain . "/mobile_register?app_user_id=" . $appUserID . '&app_id=2'
                ),

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
