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
            $data = Auth::getUser();
            $user_id = $data['user_id'];
            $role_id = DB::select('select `role_id` from `gt_relation_user_role` where `user_id`='.$user_id);
            if($role_id[0]->role_id){}
            //data要包含role信息 然后把导航信息赋给$data
        }
        else{$data = array();}

        $view->with('data',$data);
    });

    Route::get('/homel', function(){
        return $view =View::make('home')->nest('top','format.top');
});
    Route::get('/logout', function(){
        Auth::logout();
        return $view =View::make('home')->nest('top','format.top');
    });
    Route::get('/login', function(){
        if(Auth::check()){
            return $view =View::make('home')->nest('top','format.top');
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

            return $view =View::make('home')->nest('top','format.top');

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
        $header = Header::find(1)->role();
        print_r( $header);
    });
    Route::get('/test1', function(){
        $roles = User::find(888928)->roles;
//        if($roles instanceof \Illuminate\Database\Eloquent\Collection){
//            $roles->get(0);
//        }
        foreach($roles as $role){
            echo $role->pivot->label;
//            if($role instanceof \Illuminate\Database\Eloquent\Model){
//                print_r($role->getAttributes());
//                $relations = $role->getRelations();
//                foreach($relations as $relation){
//                    if($relation instanceof \Illuminate\Database\Eloquent\Model){
//                        print_r($relation->getAttributes());
//                    }
//                }
//            }

        }
//        print_r ($roles->get(0));
    });

});
Route::get('/userid', function(){
    if(Auth::check()){
        $data = Auth::getUser();
        $user_id = $data['user_id'];
        $role_id = DB::select('select `role_id` from `gt_relation_user_role` where `user_id`='.$user_id);
        print_r($role_id[0]->role_id);
    };
});

