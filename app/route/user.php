<?php
Route::get('/user/detail/{id}', function($id){
    Layout::setHighlightHeader('nav_用户列表');
    $user = cache_user($id);

    layout::appendBreadCrumbs($user->nickname);

    $weChatProfile = cache_weChat_profile($id);
    return View::make('layout')->nest('content', 'user.detail_mgr',
        array('user' => $user, 'weChatProfile' => $weChatProfile));
});

Route::get('/user', function(){
    Layout::setHighlightHeader('nav_用户列表');

    $queries = Input::all();
    $userModel = new User();

    $users = $userModel->search($queries);
    adjustTimeStamp($users);

    $privileges = option_user_privilege();
    $privileges[''] = '会员类型';

    $sexy = option_sexy();

    if(Input::get('ajax') || Request::ajax()){
        return rest_success(
            array('users' => $users->toArray(), 'queries' => $queries));
    }else{
        return View::make('layout')->nest('content', 'user.user_mgr',
            array('users' => $users, 'queries' => $queries, 'privileges' => $privileges, 'sexy' => $sexy));
    }
});

Route::get('/account', function(){
    Layout::setHighlightHeader('nav_账户列表');

    $queries = Input::all();
    !isset($queries['purpose']) && $queries['purpose'] = Sports\Constant\Finance::PURPOSE_ACCOUNT;

    $accountModel = new Account();

    $accounts = $accountModel->search($queries);

    $purposes = option_account_type();
    $purposes[''] = '账户类型';

    return View::make('layout')->nest('content', 'user.account_mgr',
        array('accounts' => $accounts, 'queries' => $queries , 'purposes' => $purposes));
});

//Route::get('/app', function(){
//    Layout::setHighlightHeader('nav_微信用户列表');
//
//    $queries = Input::all();
//
//    $appUserModel = new RelationUserApp();
//
//    $appUsers = $appUserModel->search($queries);
//
//    $appTypes = option_app_type();
//    $appTypes[''] = '应用类型';
//
//    return View::make('layout')->nest('content', 'user.app_user_mgr',
//        array('appUsers' => $appUsers, 'queries' => $queries , 'appTypes' => $appTypes));
//});

Route::any('/set_receive_sms_telephone', array('before' => 'auth', function(){
    Layout::setHighlightHeader('nav_预订信息短信通知手机号码设定');

    $user = Auth::getUser();
    if(Request::isMethod('post')){
        $rules = array(
            'telephone' => 'required|digits:11',
        );
        $messages = array(
            'required' => '请确保每项都填入了您的信息',
            'telephone.digits' => '请输入有效的电话号码',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) {
            return View::make('layout')->nest('content', 'user.set_receive_sms_telephone',
                array('user' => $user, 'errors' => $validator->messages()));
        }

        $user->receive_sms_telephone = Input::get('telephone');
        $user->save();
        return Redirect::to('order_court_manage');
    }
    return View::make('layout')->nest('content', 'user.set_receive_sms_telephone', array('user' => $user));
}));


Route::get('/billing_buyer/{curTab?}', array('before' => 'auth', function ($curTab) {
    Layout::setHighlightHeader('nav_账户明细（用户侧）');

    $tabs = array(
        'account_balance' => array(
            'label' => '账户收支明细',
            'url' => '/billing_buyer/account_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_ACCOUNT,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
        'points_balance' => array(
            'label' => '积分明细',
            'url' => '/billing_buyer/points_balance',
            'query' => array(
                'purpose' => \Sports\Constant\Finance::PURPOSE_POINTS,
                'billing_type' => \Sports\Constant\Finance::ACCOUNT_BALANCE
            )
        ),
    );

    $queries = Input::all();

    $queries = array_merge($queries, $tabs[$curTab]['query']);

    $defaultQueries = array();
    $user = Auth::getUser();
    if ($user instanceof User) {
        $defaultQueries['user_id'] = $user->user_id;
    }

    $billingStagingModel = new BillingStaging();
    $billingStagings = $billingStagingModel->search(array_merge($queries, $defaultQueries));

    return View::make('layout')->nest('content', 'user.billing_buyer',
        array('tabs' => $tabs, 'curTab' => $curTab, 'queries' => $queries, 'billingStagings' => $billingStagings));
}));

Route::get('/wechat/list', function(){
    Layout::setHighlightHeader('nav_微信用户列表');
    $queries = Input::all();

    $wechatUserModel = new weChatUserProfile();
    $wechatUsers = $wechatUserModel->search($queries);

    return View::make('layout')->nest(
        'content', 'user.wechat_user_list', array('wechatUsers' => $wechatUsers, 'queries' => $queries));
});