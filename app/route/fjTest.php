<?php

Route::group(array('prefix' => 'fj'), function(){

    Route::get('/', function(){
        return 'I am fj';
    });

    View::creator('format.top', function($view)
    {
        if(Auth::check()){
            $user = Auth::getUser();}
        else{$user = array();}

        $view->with('user',$user);
    });
    View::creator('format.header', function($view)
    {
        if(Auth::check()){
            $user = Auth::getUser();
            $user_id = $user['user_id'];
            $roles = user::find($user_id)->roles;

            $roleIds = array();
            foreach($roles as $role){
                $roleIds[] = $role->role_id;
            }

            $headers = Config::get('acl.headers');

            $allRolesHeaders = Config::get('acl.roles_headers');

            $acl = array();
            foreach($allRolesHeaders as $roleId => $rolesHeaders){
                if(in_array($roleId, $roleIds)){
                    $acl = array_merge($acl, $rolesHeaders);
                }
            }
            $data =array('headers' => $headers, 'acl' => $acl);
        }
        else{$data =array();}

        $view->with('data',$data);
    });

    Route::get('/homel', function(){

        return $view = View::make('home')->nest('top','format.top')->nest('header', 'format.header');

    });
    Route::get('/logout', function(){
        Auth::logout();
        return $view =View::make('home')->nest('top','format.top')->nest('header', 'format.header');
    });
    Route::get('/login', function(){
        if(Auth::check()){
            return $view =View::make('home')->nest('top','format.top')->nest('header', 'format.header');
        }
        else{
            return $view = View::make('login')->nest('header', 'format.header');
        }
    });
    Route::post('/logining', function(){
        $nickname = Input::get('nickname');
        $password = Input::get('password');
        $isNickLog = Auth::attempt(array('nickname' => $nickname, 'password' => $password));
        $isTeleLog = Auth::attempt(array('telephone' => $nickname, 'password' => $password));
        if ($isNickLog | $isTeleLog){

            return $view =View::make('home')->nest('top','format.top')->nest('header', 'format.header');

        }
        else {echo '登陆失败';}
    });
    Route::get('/register', function(){
        if(Auth::check()){
            return $view =View::make('home')->nest('top','format.top');
        }
        else{
            return $view = View::make('register');
        }
    });
    Route::get('/test', function(){
        $header = User::find(888928)->roles;
        print_r( $header);
    });


});
Route::get('/userid', function(){
    if(Auth::check()){
        $data = Auth::getUser();
        $user_id = $data['user_id'];
        $role_id = DB::select('select `role_id` from `gt_relation_user_role` where `user_id`='.$user_id);
        print_r($user_id);
    };
});

