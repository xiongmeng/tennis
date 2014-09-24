<?php
View::creator('mobile_layout', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
});

View::creator('mobile_layout_hall', function (\Illuminate\View\View $view) {
    $view->nest('header', 'format.mobile.header')->nest('footer', 'format.mobile.footer');
});

Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT']), function () {
    Route::get('/jcbd', function () {
        if (Auth::check()) {
            $user = Auth::getUser();

            RelationUserApp::whereUserId($user->user_id)->whereAppId(APP_WE_CHAT)->delete();
            Auth::logout();
        }
    });

    Route::get('/login/{nickname}', function ($nickname) {
        Auth::login(User::whereNickname($nickname)->first());
    });

    Route::get('/logout', function () {
        Auth::logout();
    });

    Route::get('/auto_register', function () {
        $appUserId = Input::get('app_user_id');
        $appId = APP_WE_CHAT;
        $app = RelationUserApp::where('app_user_id', '=', $appUserId)->first();
        if (!$app) {
            $weChatUserProfile = weChatUserProfile::findOrFail($appUserId);
            DB::beginTransaction();
            $nickname = 'wx_' . $weChatUserProfile->nickname;
            $user = User::whereNickname($nickname)->first();
            if (empty($user)) {
                $user = new User;
                $user->nickname = $nickname;
                $user->save();
            }

            $app = new RelationUserApp;
            $app->user_id = $user->user_id;
            $app->app_id = $appId;
            $app->app_user_id = $appUserId;
            $app->save();

            DB::commit();
        }

        Auth::loginUsingId($app->user_id, true);
        return Redirect::to(url_wrapper('/mobile_buyer'));
    });

    Route::any('/mobile_bond', function () {
        $queries = Input::all();
        $app = RelationUserApp::where('app_user_id', '=', $queries['app_user_id'])->first();
        if ($app) {
            return Redirect::to(url_wrapper('/mobile_buyer'));
        }

        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '绑定';

        if (Request::isMethod('post')) {
            Validator::extend('user_auth', function ($attribute, $value, $parameters) {
                $password = $parameters[0];
                if ($password) {
                    return Auth::attempt(array('nickname' => $value, 'password' => $password), true) ||
                    Auth::attempt(array('telephone' => $value, 'password' => $password), true);
                }
                return false;
            });

            $rules = array(
                'nickname' => "required|user_auth:" . (isset($queries['password']) ? $queries['password'] : ''),
                'password' => 'required|between:6,20',
            );
            $messages = array(
                'required' => '请确保每项都填入了您的信息',
                'nickname.user_auth' => '账号或者密码错误',
                'between' => '密码需要在6-20位之间',
            );

            $validator = Validator::make(Input::all(), $rules, $messages);
            if (!$validator->fails()) {
                $user = Auth::getUser();
                $userID = $user->user_id;
                $app = RelationUserApp::whereUserId($userID)->whereAppId(APP_WE_CHAT)->first();
                if (!$app) {
                    $app = new RelationUserApp;
                    $app->user_id = $userID;
                    $app->app_id = $queries['app_id'];
                    $app->app_user_id = $queries['app_user_id'];
                    $app->save();
                } else {
                    if ($app instanceof RelationUserApp) {
                        $app->app_user_id = $queries['app_user_id'];
                        $app->save();
                    }
                }
                $wxUserProfile = weChatUserProfile::whereOpenid($app->app_user_id)->first();
                return View::make('mobile_layout')->nest('content', 'mobile.bond_success',
                    array('user' => $user, 'wxUserProfile' => $wxUserProfile));
            }
            return View::make('mobile_layout')->nest('content', 'mobile.bond',
                array('queries' => $queries, 'errors' => $validator->messages()));
        }
        return View::make('mobile_layout')->nest('content', 'mobile.bond', array('queries' => $queries));
    });

    Route::get('/password_reset_success', function () {
        return View::make('mobile_layout')->nest('content', 'mobile.password_reset_success');
    });

    Route::any('/get_password', function () {
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '找回密码';
        MobileLayout::$previousUrl = url_wrapper(Input::get('redirect', '/mobile_bond'));
        $queries = Input::all();

        $error = '';
        if (Request::isMethod('post')) {
            $telephone = Input::get('telephone');
            $iCode = rand(100000, 999999);
            if (empty($telephone) && !is_numeric($telephone) && strlen($telephone) != 11) {
                $error = '必须输入合法的手机号';
            } else {
                $user = User::where('telephone', '=', $telephone)->first();
                if ($user) {
                    try {
                        Sms::sendSync($telephone, '您的网球通账号密码已被重置为' . $iCode . '感谢您对网球通的支持。以后打球不办卡，办卡就找【网球通】。', '');
                    } catch (Exception $e) {
                    }
                    $user->password = Hash::make($iCode);
                    $user->save();
                    return View::make('mobile_layout')->nest('content', 'mobile.password_reset_success');
                } else {
                    $error = '手机号未被注册';
                }
            }
        }
        return View::make('mobile_layout')->nest('content', 'mobile.get_password', array('queries' => $queries, 'error' => $error));
    });
});

Route::group(array('domain' => $_ENV['DOMAIN_WE_CHAT'], 'before' => 'weChatAuth'), function () {
    Route::get('/', function () {
        return Redirect::to('/mobile_home/reserve/recommend');
    });

    Route::get('/mobile_home/instant', function () {
        MobileLayout::$activeService = 'instant';

        $queries = Input::all();

        $curDate = date('Y-m-d');
        $queries['event_date_start'] = $curDate;

        $queries['state'] = array('on_sale');

        $instantOrder = new InstantOrder();
        $hallPriceAggregates = $instantOrder->searchHallPriceAggregate($queries, 20);

        $hallIds = array();
        foreach ($hallPriceAggregates as $hallPriceAggregate) {
            $hallIds[$hallPriceAggregate->hall_id] = $hallPriceAggregate->hall_id;
        }

        $halls = array();
        if (count($hallIds) > 0) {
            $hallDbResults = Hall::with('HallImages', 'Envelope')->whereIn('id', $hallIds)->get();
            foreach ($hallDbResults as $hallDbResult) {
                $halls[$hallDbResult->id] = $hallDbResult;
            }
        }

        $weekdayOption = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
        $dates = array('不限');
        for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = sprintf('%s（%s）', date('m月d日', $time), $weekdayOption[date('w', $time)]);
        }

        $hours = array('不限');
        for ($i = 8; $i < 23; $i++) {
            $hours[$i] = sprintf('%s时 - %s时', $i, $i + 1);
        }
        return View::make('mobile_layout_hall')->nest('content', 'mobile.instant_hall',
            array('queries' => $queries, 'hallPriceAggregates' => $hallPriceAggregates,
                'halls' => $halls, 'dates' => $dates, 'hours' => $hours));

    });

    Route::get('/mobile_home/reserve/{curType?}', function ($curType) {
        MobileLayout::$activeService = 'reserve';

        $types = array(
            'recommend' => array(
                'label' => '全部场馆',
                'url' => '/mobile_home/reserve/recommend',
            ),
            'nearby' => array(
                'label' => '附近场馆',
                'url' => '/mobile_home/reserve/nearby',
            ),
            'ordered' => array(
                'label' => '常订场馆',
                'url' => '/mobile_home/reserve/ordered',
            ),
        );

        $queries = Input::all();
        $hallDbResults = array();
        $halls = array();

        if ($curType == 'recommend') {
            $hallDbResults = Hall::with('HallPrices')->whereStat(2)
                ->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($queries) {
                    if (!empty($queries['hall_name'])) {
                        $builder->where('name', 'like', '%' . $queries['hall_name'] . '%');
                    }
                })->get();
        } else {
            if ($curType == 'nearby') {
                $appUserID = Input::get('app_user_id');
                $time = strtotime(date('Y-m-d', time()));
                $location = WXLocation::where('openid', '=', $appUserID)->where('creattime', '<', $time)->orderBy('creattime', 'desc')->first();
                if ($location) {
                    $lat = $location->lat;
                    $lon = $location->lon;
                    $Halls = DB::select('select `hall_id`,`long`,`lat`,ACOS(SIN((' . $lat . ' * 3.1415) / 180 ) * SIN((`lat` * 3.1415) / 180 ) + COS((' . $lat . '* 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS((' . $lon . ' * 3.1415) / 180 - (`long` * 3.1415) / 180 ) ) * 6380 as description from `gt_hall_tiny` as a join `gt_hall_map` as b on a.id=b.`hall_id` where
                          a.`stat` =2 and
                          b.`lat` > ' . $lat . '-1 and
                          b.`lat` < ' . $lat . '+1 and
                          b.`long` > ' . $lon . '-1 and
                          b.`long` <  ' . $lon . '+1 order by description asc limit 7');
                } else {
                    $Halls = array();
                }
            } elseif ($curType == 'ordered') {
                $user = Auth::getUser();
                $Halls = ReserveOrder::whereUserId($user->user_id)->orderBy('event_date', 'desc')->select('hall_id')->distinct()->get();
            }

            $hallIds = array();
            foreach ($Halls as $Hall) {
                $hallIds[$Hall->hall_id] = $Hall->hall_id;
            }

            if (count($hallIds) > 0) {
                $hallDbResults = Hall::with('HallPrices')->whereIn('id', $hallIds)->get();
            }
        }

        foreach ($hallDbResults as $hallDbResult) {
            $halls[$hallDbResult->id] = $hallDbResult;
        }

        return View::make('mobile_layout_hall')->nest('content', 'mobile.reserve_hall',
            array('curType' => $curType, 'types' => $types, 'halls' => $halls, 'queries' => $queries));
    });

    Route::get('/mobile_buyer', function () {
        $user = Auth::getUser();
        MobileLayout::$activeService = 'center';
        $userID = $user['user_id'];

        $instantModel = new InstantOrder();

        $pending = $resPaying = $insPaying = $payed = 0;
        $instant = $instantModel->select(array('state', DB::raw('COUNT(1) AS count')))->where('buyer', '=', $userID)->groupBy('state')->get();
        foreach ($instant as $ins) {
            if ($ins->state == 'paying') {
                $insPaying = $ins->count;
            } elseif ($ins->state == 'payed') {
                $payed = $ins->count;
            }
        }

        $reserve = ReserveOrder::where('user_id', '=', $user->user_id)->select(array('stat', DB::raw('COUNT(1) AS count')))->groupBy('stat')->get();
        foreach ($reserve as $res) {
            if ($res->stat == '0') {
                $pending = $res->count;
            } elseif ($res->stat == '1') {
                $resPaying = $res->count;
            }
        }

        $app = RelationUserApp::whereUserId($user->user_id)->whereAppId(APP_WE_CHAT)->first();
        $wxUserProfile = weChatUserProfile::whereOpenid($app->app_user_id)->first();

        return View::make('mobile_layout_hall')->nest('content', 'mobile.mobile_buyer', array('user' => $user, 'wxUserProfile' => $wxUserProfile,
            'insPaying' => $insPaying, 'payed' => $payed, 'resPaying' => $resPaying, 'pending' => $pending));

    });

    Route::get('/mobile_buyer_order', function () {
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = "我的即时订单";
        MobileLayout::$previousUrl = '/mobile_buyer';

        $queries = Input::all();

        $user = Auth::getUser();
        $userID = $user['user_id'];
        $instantModel = new InstantOrder();
        $queries['buyer'] = $userID;
        $label = Input::get('state');
        if (!$label) {
            $label = 'all';
        }

        $instants = $instantModel->search($queries);
        return View::make('mobile_layout')->nest('content', 'mobile.order_buyer',
            array('user' => $user, 'instants' => $instants, 'label' => $label));

    });

    Route::get('/hall_reserve', function () {
        MobileLayout::$activeService = 'reserve';
        MobileLayout::$title = '填写订单';
        MobileLayout::$previousUrl = URL::previous();

        $hallID = Input::get('hall_id');
        $hall = Hall::find($hallID);
        $user = Auth::getUser();

        $weekdayOption = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
        $dates = array();
        for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = sprintf('%s（%s）', date('m月d日', $time), $weekdayOption[date('w', $time)]);
        }

        $businesses = explode('-', $hall->business);
        $start = count($businesses) > 0 ? max(intval(str_replace(':00', '', $businesses[0])), 0) : 7;
        $end = count($businesses) > 1 ? min(intval(str_replace(':00', '', $businesses[1])), 24) : 24;
        $hours = array('不限');
        for ($i = $start; $i <= $end; $i++) {
            $hours[$i] = sprintf('%s时', $i, $i + 1);
        }

        return View::make('mobile_layout')->nest('content', 'mobile.hall_reserve',
            array('hall' => $hall, 'user' => $user, 'dates' => $dates, 'hours' => $hours));
    });

    Route::get('/mobile_court_buyer/{hallID?}', function ($hallID) {
        MobileLayout::$activeService = 'instant';
        MobileLayout::$previousUrl = URL::previous();

        $hall = Hall::findOrFail($hallID);

        $activeDate = Input::get('date');
        $activeDateTimeStamp = empty($activeDate) ? time() : strtotime($activeDate);
        $activeDate = date('Y-m-d', $activeDateTimeStamp);

        $dates = array();
        for ($i = 0; $i < WORKTABLE_SUPPORT_DAYS_LENGTH; $i++) {
            $time = strtotime("+$i day");
            $dates[date('Y-m-d', $time)] = $time;
        }

        $instantOrders = InstantOrder::orderBy('start_hour', 'asc')
            ->where('hall_id', '=', $hallID)->where('event_date', '=', $activeDate)->get();

        $formattedInstants = array();
        foreach ($instantOrders as $instant) {
            !isset($formattedInstants[$instant->court_id]) && $formattedInstants[$instant->court_id] = array();
            $formattedInstants[$instant->court_id][$instant->start_hour] = $instant;
            $instant['select'] = false;
        }

        $courts = Court::where('hall_id', '=', $hallID)->get();

        MobileLayout::$title = $hall->name;
        return View::make('mobile_layout')->nest('content', 'mobile.court_buyer', array(
            'halls' => array($hall), 'dates' => $dates, 'hallID' => $hallID, 'weekdayOption' => weekday_option(),
            'activeDate' => $activeDate, 'courts' => $courts, 'formattedInstants' => $formattedInstants,
            'loginUserId' => Auth::getUser()->user_id, 'instantOrders' => $instantOrders, 'noMoney' => no_money_array()
        ));
    });

    Route::post('/submit_reserve_order', function () {
        $queries = Input::all();
        $order = new ReserveOrder;
        $order->hall_id = $queries['hall_id'];
        $order->user_id = $queries['user_id'];
        $order->start_time = $queries['start_time'];
        $order->end_time = $queries['end_time'];
        $order->createtime = time();
        $order->cost = $queries['price'];
        $order->court_num = $queries['court_num'];
        $order->event_date = strtotime($queries['event_date']);
        $order->createuser = $queries['user_id'];
        $order->save();

        Notify::doNotify('mgr_reserve_order_created', $order->id);

        return Redirect::to(url_wrapper('/reserve_order_buyer'));
    });

    Route::get('/reserve_order_buyer', function () {
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '我的预约订单';
        MobileLayout::$previousUrl = '/mobile_buyer';


        //展示预定订单
        $user = Auth::getUser();
        $stat = Input::get('stat');
        if (isset($stat)) {
            $orderDbResults = ReserveOrder::with('Hall')->where('user_id', '=', $user->user_id)->where('stat', '=', $stat)->orderBy('event_date', 'asc')->get();
        } else {
            $orderDbResults = ReserveOrder::with('Hall')->where('user_id', '=', $user->user_id)->orderBy('event_date', 'desc')->get();
            $stat = '7';
        }

        adjustTimeStamp($orderDbResults);

        $reserves = array();
        foreach ($orderDbResults as $orderDbResult) {
            $reserves[$orderDbResult->id] = $orderDbResult;
        }

        return View::make('mobile_layout')->nest('content', 'mobile.reserve_order_buyer',
            array('reserves' => $reserves, 'stat' => $stat, 'noMoney' => no_money_array()));
    });

    Route::get('/pay_success', function () {
        return View::make('mobile_layout_hall')->nest('content', 'mobile.pay_success');
    });

    Route::get('/pay_fail', function () {
        return View::make('mobile_layout_hall')->nest('content', 'mobile.pay_fail');
    });

    Route::post('/telValidCodeMake', function () {
        $telephone = Input::get('telephone');
        $notExists = Input::get('not_exists', false);
        $ttl = Input::get('ttl', 2); //以分为单位
        $rules = array(
            'telephone' => 'required|digits:11' . ($notExists ? '|telephone_not_exist' : ''),
        );
        $messages = array(
            'required' => '请确保每项都填入了您的信息',
            'telephone.digits' => '请输入有效的电话号码',
            'telephone.telephone_not_exist' => '该电话号码已经注册过网球通帐号',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return rest_success(array('status' => 1, 'errors' => $validator->messages()));
        } else {
            $cacheKey = CACHE_PREFIX_VALID_CODE . $telephone;

            $validCode = array('ttl' => 0);
            $curTime = time();
            if (Cache::has($cacheKey)) {
                $validCode = Cache::get($cacheKey);
            } else {
                $code = rand(1000, 9999);
                $validCode = array('code' => $code, 'expire' => $curTime + $ttl * 60, 'created_time' => $curTime);
                try {
                    Sms::sendSync($telephone, '您的手机验证码为' . $code . '感谢您对网球通的支持。以后打球不办卡，办卡就找【网球通】。', '');
                } catch (Exception $e) {

                }
                Cache::put($cacheKey, $validCode, $ttl);
            }
            $validCode['ttl'] = $validCode['expire'] - $curTime;
            return rest_success(array('status' => 2, 'validCode' => array_except($validCode, 'code')));
        }
    });

    Route::any('/mobile_change_telephone', function () {
        $queries = Input::all();
        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '更换绑定的手机';
        MobileLayout::$previousUrl = url_wrapper('/mobile_buyer');
        $user = Auth::getUser();

        $telephone = Input::get('telephone');
        $validCode = array('ttl' => 0);
        if ($telephone) {
            $cacheKey = CACHE_PREFIX_VALID_CODE . $telephone;
            $validCode = Cache::get($cacheKey, array('ttl' => 0));
            if (isset($validCode['expire'])) {
                $validCode['ttl'] = $validCode['expire'] - time();
            }
        }

        if (Request::isMethod('post')) {
            $rules = array(
                'telephone' => 'required|digits:11|telephone_not_exist',
                'validcode' => 'required|in:' . (isset($validCode['code']) ? $validCode['code'] : '')
            );
            $messages = array(
                'required' => '请确保每项都填入了您的信息',
                'telephone.digits' => '请输入有效的电话号码',
                'telephone.telephone_not_exist' => '该电话号码已经注册过网球通帐号',
                'validcode.in' => '验证码不正确',
            );

            $validator = Validator::make(Input::all(), $rules, $messages);
            if ($validator->fails()) {
                return View::make('mobile_layout')->nest('content', 'mobile.change_telephone',
                    array('user' => $user, 'queries' => $queries,
                        'errors' => $validator->messages(), 'validCode' => array_except($validCode, array('code'))));
            }

            $user->telephone = $telephone;
            $user->save();
            return View::make('mobile_layout')->nest('content', 'mobile.change_telephone_success', array('user' => $user));
        }
        return View::make('mobile_layout')->nest('content', 'mobile.change_telephone',
            array('user' => $user, 'queries' => $queries, 'validCode' => array_except($validCode, 'code')));
    });

    Route::any('/mobile_change_user', function () {
        $queries = Input::all();
        $user = Auth::getUser();

        MobileLayout::$activeService = 'center';
        MobileLayout::$title = $user->telephone ? '更换绑定的账号' : '绑定网球通账号';
        MobileLayout::$previousUrl = url_wrapper('/mobile_buyer');

        $app = RelationUserApp::whereUserId($user->user_id)->whereAppId(APP_WE_CHAT)->first();

        if (Request::isMethod('post')) {
            $rules = array(
                'nickname' => "required|user_auth:" . (isset($queries['password']) ? $queries['password'] : ''),
                'password' => 'required|between:6,20',
            );
            $messages = array(
                'required' => '请确保每项都填入了您的信息',
                'nickname.user_auth' => '账号或者密码错误',
                'between' => '密码需要在6-20位之间',
            );

            $validator = Validator::make(Input::all(), $rules, $messages);
            if (!$validator->fails()) {
                $user = Auth::getUser();
                $app->user_id = $user->user_id;
                $app->save();

                $wxUserProfile = weChatUserProfile::whereOpenid($app->app_user_id)->first();
                return View::make('mobile_layout')->nest('content', 'mobile.bond_success',
                    array('user' => $user, 'wxUserProfile' => $wxUserProfile));
            }
            return View::make('mobile_layout')->nest('content', 'mobile.change_user',
                array('queries' => $queries, 'app' => $app, 'user' => $user, 'errors' => $validator->messages()));
        }
        return View::make('mobile_layout')->nest('content', 'mobile.change_user',
            array('queries' => $queries, 'app' => $app, 'user' => $user));
    });

    Route::any('/mobile_register', function () {
        $queries = Input::all();

        MobileLayout::$activeService = 'center';
        MobileLayout::$title = '注册网球通账号';
        MobileLayout::$previousUrl = url_wrapper('/mobile_buyer');

        $user = Auth::getUser();
        $app = RelationUserApp::whereUserId($user->user_id)->whereAppId(APP_WE_CHAT)->first();

        $telephone = Input::get('telephone');
        $validCode = array('ttl' => 0);
        if ($telephone) {
            $cacheKey = CACHE_PREFIX_VALID_CODE . $telephone;
            $validCode = Cache::get($cacheKey, array('ttl' => 0));
            if (isset($validCode['expire'])) {
                $validCode['ttl'] = $validCode['expire'] - time();
            }
        }

        if (Request::isMethod('post')) {
            $rules = array(
                'nickname' => "required|user_unique:" . $user->user_id,
                'password' => 'required|between:6,20',
                'telephone' => 'required|digits:11|telephone_not_exist',
                'validcode' => 'required|in:' . (isset($validCode['code']) ? $validCode['code'] : '')
            );
            $messages = array(
                'required' => '请确保每项都填入了您的信息',
                'nickname.user_unique' => '该昵称已经注册过网球通帐号',
                'password.between' => '密码需要在6-20位之间',
                'telephone.digits' => '请输入有效的电话号码',
                'telephone.telephone_not_exist' => '该电话号码已经注册过网球通帐号',
                'validcode.in' => '验证码不正确',
            );

            $validator = Validator::make(Input::all(), $rules, $messages);
            if (!$validator->fails()) {
                $user->nickname = $queries['nickname'];
                $user->telephone = $queries['telephone'];
                $user->password = Hash::make($queries['password']);
                $user->save();

                $wxUserProfile = weChatUserProfile::whereOpenid($app->app_user_id)->first();
                return View::make('mobile_layout')->nest('content', 'mobile.register_success',
                    array('user' => $user, 'wxUserProfile' => $wxUserProfile));
            }
            return View::make('mobile_layout')->nest('content', 'mobile.register',
                array('queries' => $queries, 'app' => $app, 'user' => $user,
                    'errors' => $validator->messages(), 'validCode' => $validCode));
        }
        return View::make('mobile_layout')->nest('content', 'mobile.register',
            array('queries' => $queries, 'app' => $app, 'user' => $user, 'validCode' => $validCode));
    });

    Route::any('recharge', function () {
        MobileLayout::$title = '充值';
        MobileLayout::$activeService = 'center';
        MobileLayout::$previousUrl = url_wrapper('/mobile_buyer');

        $user = Auth::getUser();
        $noMoney = no_money_array();
        if (Request::isMethod('post')) {
            $rules = array(
                'money' => "required|integer|min:1|max:100000",
            );
            $messages = array(
                'money.required' => '请确保每项都填入了您的信息',
                'money.integer' => '额度必须为整数',
                'money.min' => '额度最小为1',
                'money.max' => '额度最大为100000',
            );

            $validator = Validator::make(Input::all(), $rules, $messages);
            if (!$validator->fails()) {
                //预先生成recharge表
                $recharge = new Recharge();
                $recharge->user_id = $user->user_id;
                $recharge->money = Input::get('money');
                $recharge->stat = 1; //初始化
                $recharge->createtime = time();
                $recharge->save();

                $noMoney['adviseForwardUrl'] = url_wrapper(sprintf('/recharge/alipay?recharge_id=%s', $recharge->id));
                $noMoney['weChatPayUrl'] = sprintf('/recharge/wechatpay?recharge_id=%s', $recharge->id);

                return View::make('mobile_layout')->nest('content', 'mobile.recharge',
                    array('recharge' => $recharge, 'noMoney' => $noMoney));
            }
            return View::make('mobile_layout')->nest('content', 'mobile.recharge',
                array('errors' => $validator->messages(), 'noMoney' => $noMoney));
        }


        return View::make('mobile_layout')->nest('content', 'mobile.recharge', array('noMoney' => $noMoney));
    });
});



