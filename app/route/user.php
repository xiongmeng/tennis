<?php
Route::get('/user/detail/{id}', function($id){
    $user = cache_user($id);
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

    $isBondWeChat = option_yes_no();
    $isBondWeChat[''] = '是否绑定微信';

    return View::make('layout')->nest('content', 'user.user_mgr',
        array('users' => $users, 'queries' => $queries, 'privileges' => $privileges, 'sexy' => $sexy,
            'isBondWeChat' => $isBondWeChat));
});

Route::get('/account', function(){
    $queries = Input::all();
    !isset($queries['purpose']) && $queries['purpose'] = Sports\Constant\Finance::PURPOSE_ACCOUNT;

    $accountModel = new Account();

    $accounts = $accountModel->search($queries);

    $purposes = option_account_type();
    $purposes[''] = '账户类型';

    return View::make('layout')->nest('content', 'user.account_mgr',
        array('accounts' => $accounts, 'queries' => $queries , 'purposes' => $purposes));
});

Route::get('/app', function(){
    $queries = Input::all();

    $appUserModel = new RelationUserApp();

    $appUsers = $appUserModel->search($queries);

    $appTypes = option_app_type();
    $appTypes[''] = '应用类型';

    return View::make('layout')->nest('content', 'user.app_user_mgr',
        array('appUsers' => $appUsers, 'queries' => $queries , 'appTypes' => $appTypes));
});