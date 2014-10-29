<?php
Route::get('/user/detail/{id}', function($id){
    $user = cache_user($id);
    $weChatProfile = cache_weChat_profile($id);

    return View::make('layout')->nest('content', 'user.detail_mgr',
        array('user' => $user, 'weChatProfile' => $weChatProfile));
});

Route::get('/user', function(){

    $queries = Input::all();
    $userModel = new User();

    $users = $userModel->search($queries);
    adjustTimeStamp($users);

    $privileges = option_user_privilege();
    $privileges[''] = '会员类型';

    $sexy = option_sexy();

    $isBondWeChat = option_yes_no();
    $isBondWeChat[''] = '是否绑定微信';

    return View::make('layout')->nest('content', 'user.list_mgr',
        array('users' => $users, 'queries' => $queries, 'privileges' => $privileges, 'sexy' => $sexy,
            'isBondWeChat' => $isBondWeChat));
});