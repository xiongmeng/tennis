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
//        $menu = Config::get('/packages/cooper/wechat/menu.WeChatMenu');
//        $client = new \Cooper\Wechat\WeChatClient();
//        $client->setMenu($menu[0]);

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

            if ($Event == 'click') { //CLICK事件

                $key = $message['key']; //获取当前菜单key

                /**
                 *
                 *推荐场馆
                 * */
//                if ($key == 'Recommend_Court') {
//                    //标题
//
//                    $res['title'] = array('name' => "推荐场馆",
//                        'PicUrl' => "http://" . $currentdomain . "/assets/img/weixin/TopPic/Recommend_Court.jpg",
//                        'Url' => "http://" . $currentdomain . "/hall_detail?hall_id=8888&app_user_id=" . $appUserID
//                    );
//                    //内容
//                    $Halls = HallActive::where('type', '=', 1, 'limit 7')->get();
//                    $temp = array();
//
//
//                    foreach ($Halls as $key => $Hall) {
//                        $array = Hall::where('id', '=', $Hall['hall_id']);
//                        $temp = array_merge($temp, $array);
//                    }
//
//                    foreach ($temp as $key => $value) {
//                        $hallID = $temp[$key]['id'];
//                        $temp[$key]['Url'] = "http://" . $currentdomain . "/hall_detail/hall_id" . $hallID . ".html?app_user_id=" . $appUserID;
//                        $temp[$key]['PicUrl'] = "http://" . $currentdomain . "/assets/img/weixin/CourtPic/$hallID.jpg";
//                    }
//                    //添加可搜索项
//                    $temp[8] = array(
//                        'name' => "搜索更多场馆",
//                        'PicUrl' => "http://" . $currentdomain . "/assets/img/weixin/ListPic/search.png",
//                        'Url' => "http://" . $currentdomain . "/hall_list?app_user_id=" . $appUserID);
//
//
//                    $res['item'] = $temp;
//
//                    $res['content'] = '推荐场馆';
//
//                    if ($res) {
//                        $reply = $server->getXml4RichMsgByArray($res);
//                    } else {
//                        $reply = $server->getXml4Txt('抱歉，出错了呦');
//                    }
//                    echo $reply;
//                }

                /**
                 *
                 *附近场馆
                 */
//                if ($key == 'Nearby_Court') {
//                    $userid = $this->getUserID($appUserID);
//
//                    //内容
//                    $time = strtotime(date('Y-m-d', time()));
//                    $sql = "select `lat`,`lon` from `weixin_location` where `app_user_id`='$appUserID' and `creattime`>" . $time . " order by `creattime` desc limit 1";
//                    $temp = db::instance()->select_one($sql);
//                    if ($temp) {
//                        $lat = $temp['lat'];
//                        $lon = $temp['lon'];
//
//                        $sql = "select `hall_id`,`long`,`lat`,ACOS(SIN((" . $lat . " * 3.1415) / 180 ) * SIN((`lat` * 3.1415) / 180 ) + COS((" . $lat . " * 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS((" . $lon . " * 3.1415) / 180 - (`long` * 3.1415) / 180 ) ) * 6380 as description from `gt_hall_tiny` as a join `gt_hall_map` as b on a.id=b.`hall_id` where
//                          a.`stat` =2 and
//                          b.`lat` > " . $lat . "-1 and
//                          b.`lat` < " . $lat . "+1 and
//                          b.`long` > " . $lon . "-1 and
//                          b.`long` < " . $lon . "+1 order by description asc limit 7";
//
//                        $CourtList = db::instance()->select($sql);
//
//
//                        foreach ($CourtList as $key => $aCourt) {
//                            $courtID = $aCourt['hall_id'];
//                            $sql = "select `name` from `gt_hall_tiny` where `id`=" . $courtID;
//                            $temp = db::instance()->select_one($sql);
//                            $name = $temp['name'];
//                            $description = round($aCourt['description'], 1);
//                            $CourtList[$key]['name'] = $name . "\n约" . $description . "Km";
//                            $CourtList[$key]['Url'] = "http://" . $currentdomain . "/court_weixindetail/courtid_" . $courtID . "html?userid=" . $userid . "&app-user_id=" . $appUserID;
//                            $CourtList[$key]['PicUrl'] = "http://" . $currentdomain . "/Images/weixinImage/CourtPic/" . $courtID . ".jpg";
//                        }
//                        $CourtList[8] = array('name' => "搜索更多场馆", 'PicUrl' => "http://" . $currentdomain . "/Images/weixinImage/ListPic/search.png", 'Url' => "http://" . $currentdomain . "/court_weixin_courtList.html?userid=" . $userid . "&openid=" . $openID);
//                        $res['item'] = $CourtList;
//                        $res['content'] = '附近场馆';
//                        //标题
//                        $res['title'] = array(
//                            'name' => '附近场馆',
//                            'PicUrl' => "http://" . $currentdomain . '/Images/weixinImage/TopPic/Nearby_Court.jpg',
//                            'Url' => $CourtList[0]['Url']
//                        );
//
//
//                        $reply = $weixin->makeNews($res);
//                    } else {
//                        $reply = $weixin->makeText("您没有同意上报地理位置信息哦!点击屏幕右上角的小人，打开“提供位置信息” 就可以搜索到您附近的场馆了！");
//
//                    }
//
//                    $weixin->reply($reply);
//                }

//                /**
//                 *
//                 *常订场馆
//                 */
//                if ($key == 'Ordered_Court') {
//                    $userid = $weixin->getuserid($appUserID);
//                    if ($userid == 0) {
//                        $reply = $weixin->makeText("您还没有绑定您的网球通账号哦！\n如果你还不是网球通的会员，请选择<a href='$reg_url'>【注册网球通会员】</a>\n如果您已经是我们的会员 请选择<a href='$bond_url'>【网球通会员绑定】</a>");
//                        $weixin->reply($reply);
//                    } else {
//
//                        $sqlCourt = "select DISTINCT `hall_id` from `gt_order` where `user_id`=" . $userid . " order by `event_date` desc limit 7";
//                        $Court = db::instance()->select($sqlCourt);
//                        if (empty($Court)) {
//                            $reply = $weixin->makeText('您还没有在网球通订过场地哦！');
//                            $weixin->reply($reply);
//                        } else {
//                            $res['title'] = array(
//                                'name' => '常订场馆',
//                                'PicUrl' => "http://" . $currentdomain . '/Images/weixinImage/TopPic/Ordered_Court.jpg',
//                                'Url' => "http://" . $currentdomain . "/court_weixin_courtList.html?userid=" . $userid . "&app_user_id=" . $appUserID
//                            );
//
//                            $temp = array();
//                            foreach ($Court as $key => $aCourt) {
//                                $sql = "select `id`,`name` from `gt_hall_tiny` where `id`=" . $aCourt['hall_id'];
//                                $temp = array_merge($temp, db::instance()->select($sql));
//                            }
//
//                            foreach ($temp as $key => $value) {
//                                $courtID = $temp[$key]['id'];
//                                $temp[$key]['Url'] = "http://" . $currentdomain . "/court_weixindetail/courtid_" . $courtID . "html?userid=" . $userid . "&app_user_id=" . $appUserID;
//                                $temp[$key]['PicUrl'] = "http://" . $currentdomain . "/Images/weixinImage/CourtPic/" . $courtID . ".jpg";
//                            }
//
//
//                            $res['item'] = $temp;
//
//                            $res['content'] = '常订场馆';
//
//                            if ($res) {
//                                $reply = $weixin->makeNews($res);
//                            } else {
//                                $reply = $weixin->makeText('抱歉，出错了呦');
//                            }
//                            $weixin->reply($reply);
//                        }
//                    }
//                }

                /**
                 *
                 *入会/绑定
                 */
                if ($key == 'Add_Bond') {

                    $user = $this->getUser($appUserID);
                    if (!$user) {

                        $res = array(
                            0 => array(
                                'title' => '注册/绑定',
                                'desc' => '注册/绑定',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/test"
                            ),
                            1 => array(
                                'title' => '注册暂未开通',
                                'desc' => '注册暂未开通',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/test"
                            ),
                            2 => array(
                                'title' => '网球通会员绑定',
                                'desc' => '网球通会员绑定',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/bond?app_user_id=" . $appUserID . '&app_id=2'
                            )

                        );
                        if ($res) {
                            $reply = $server->getXml4RichMsgByArray($res);
                        } else {
                            $reply = $server->getXml4Txt('抱歉，出错了呦');
                        }
                    } else {
                        $reply = $server->getXml4Txt("您已经成功绑定网球通账号，如需解除绑定请回复字母JCBD。");
                    }
                    echo $reply;
                }
//
//
                /**
                 *
                 *会员服务
                 */
                if ($key == 'Member_Sever') {
                    $user = $this->getUser($appUserID);


                    if (!$user) {
                        $reply = $server->getXml4Txt("您还没有绑定您的网球通账号哦！\n如果你还不是网球通的会员，请选择<a href='$reg_url'>【*注册网球通会员*】</a>\n如果您已经是我们的会员 请选择<a href='$bond_url'>【网球通会员绑定】</a>");
                        echo $reply;
                    } else {

                        $res = array(
                            0 => array(
                                'title' => '会员服务',
                                'desc' => '会员服务',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/welcome"
                            ),
                            1 => array(
                                'title' => '即时订单',
                                'desc' => '即时订单',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/instant_order_buyer?app_user_id=" . $appUserID . '&app_id=2'
                            ),
                            2 => array(
                                'title' => '预约订单',
                                'desc' => '*预约订单*',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/#?app_user_id=" . $appUserID . '&app_id=2'
                            ),
                            3 => array(
                                'title' => '账户明细',
                                'desc' => '账户明细',
                                'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                                'url' => "http://" . $currentdomain . "/billing_buyer/account_balance?app_user_id=" . $appUserID . '&app_id=2'
                            ),
                        );


                            $reply = $server->getXml4RichMsgByArray($res);
                        }
                        echo $reply;

                }
                /*
                *搜索场馆
                */
//                if ($key == 'Search_Court') {
//                    $userid = $this->getUserID($appUserID);
//                    $res['title'] = array('name' => '搜索场馆',
//                        'PicUrl' => "http://" . $currentdomain . "/Images/weixinImage/TopPic/Recommend_Court.jpg",
//                        'Url' => "http://" . $currentdomain . "/court_weixin_courtList.html?userid=" . $userid . "&app_user_id=" . $appUserID
//                    );
//                    $res['item'] = array(0 => array('name' => "搜索场馆", 'PicUrl' => "http://" . $currentdomain . "/Images/weixinImage/ListPic/search.png", 'Url' => "http://" . $currentdomain . "/court_weixin_courtList.html?userid=" . $userid . "&openid=" . $openID));
//                    $res['content'] = "搜索场馆";
//                    if ($res['item']) {
//                        $reply = $weixin->makeNews($res);
//                    } else {
//                        $reply = $weixin->makeText('抱歉，出错了呦');
//                    }
//                    $weixin->reply($reply);
//
//                }
                /*
                *即时订场
                */
                if ($key == 'Instant_Order') {


                    $res = array(
                        0 => array(
                            'title' => '即时订场',
                            'desc' => '即时订场',
                            'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                            'url' => "http://" . $currentdomain . "/hall_on_sale?app_user_id=" . $appUserID . '&app_id=2'
                        ),
                        1 => array(
                            'title' => '即时订场',
                            'desc' => '即时订场',
                            'pic' => "http://" . $currentdomain . "/assets/img/logo.jpg",
                            'url' => "http://" . $currentdomain . "/hall_on_sale?app_user_id=" . $appUserID . '&app_id=2'
                        )
                    );

                    $reply = $server->getXml4RichMsgByArray($res);

                    echo $reply;
                }
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
                $reply = $server->getXml4Txt("欢迎关注网球通！我们将竭诚为你提供更方便，更低价格的的网球订场服务。");
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
