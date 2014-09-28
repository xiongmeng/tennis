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

        $host = "http://" . $_ENV['DOMAIN_WE_CHAT'];

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
                Log::debug('wechat_click_happend', $message);
                echo $server->getXml4RichMsgByArray(array(
                    0 => array(
                        'title' => '订场就找网球通',
                        'desc' => '与中网球星同“场”挥拍
2014年中国网球公开赛已经在国家网球中心打响，世界顶尖高手汇聚一场！在看巨星们比赛的时候，球友们是不是手痒难耐？
“网球通”为球友们预留了中网比赛期间国家网球中心室内外球场各个黄金时段的场地，想一想能在与大牌球星比赛赛场咫尺之遥的球场上挥拍，是多么激动人心令人艳羡！
即刻关注“网球通”微信官方公众账户：“添加朋友”>“查找公众号”>搜索“网球通”，在“即时订场”里赶快下单吧！
我们国家网球中心球场上见！',
                        'pic' => "http://wangqiuer.com/uploadfiles/court/201206/8920_50f694c5ea35c841f17623b3c53930c8.jpg",
                        'url' => $host . '/mobile_home/instant'
                    ),
                ));
            }
        }


        if ($type === 'text') { //文本输入
            $content = strtolower($message['content']);
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
