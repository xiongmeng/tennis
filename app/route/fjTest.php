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
//    View::creator('format.header', function($view)
//    {
//        if(Auth::check()){
//            $user = Auth::getUser();
//            $user_id = $user['user_id'];
//            $roles = user::find($user_id)->roles;
//            $roleIds = array();
//            foreach($roles as $role){
//                $roleIds[] = $role->role_id;
//            }
//
//            $headers = Config::get('acl.headers');
//
//            $allRolesHeaders = Config::get('acl.roles_headers');
//
//            $acl = array();
//            foreach($allRolesHeaders as $roleId => $rolesHeaders){
//                if(in_array($roleId, $roleIds)){
//                    $acl = array_merge($acl, $rolesHeaders);
//                }
//            }
//            $data =array('headers' => $headers, 'acl' => $acl);
//        }
//        else{$data =array();}
//
//        $view->with('data',$data);
//    });

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
            //返回登录前页面
            $url =  URL::previous();
            return $redirect = Redirect::to($url);

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
    Route::get('/belongstomany', function(){
        $role = User::find(888928)->roles;
        print_r($role);
    });
    Route::get('/hasone', function(){
        $detail = User::find(888929)->detail;
        print_r($detail);
    });

    Route::get('/hasmany', function(){

    });

    Route::get('/instant_order_mgr',function(){
        $queries = Input::all();
        $instantModel = new InstantOrder();

        $instants = $instantModel->search($queries);

        $states = Config::get('state.data');


        return View::make('instantOrder.order_mgr', array('instants' => $instants, 'queries' => $queries,'states' => $states))->nest('top','format.top')->nest('header', 'format.header');
    });
        Route::get('/instant_order_buyer',function(){
            $queries = Input::all();
        if(Auth::check()){
            $user = Auth::getUser();
            $userID =$user['user_id'];
        }
            $instantModel = new InstantOrder();
            $instants = $instantModel->buyer($queries,$userID);
            $states = Config::get('state.data');
            return View::make('instantOrder.order_buyer', array('instants' => $instants, 'states' => $states,'userID'=>$userID,'queries'=>$queries))->nest('top','format.top')->nest('header', 'format.header');
    });
    Route::get('/instant_order_on_sale',function(){
        $queries = Input::all();
        $instantModel = new InstantOrder();

        $instants = $instantModel->on_sale($queries);
        //foreach($instants as $instant){print_r($instant);}exit;
        if(Auth::check()){
            $user = Auth::getUser();
            $userID =$user['user_id'];
        }
        $states = Config::get('state.data');


        return View::make('instantOrder.order_on_sale', array('instants' => $instants, 'queries' => $queries,'states' => $states,'userID'=>$userID))->nest('top','format.top')->nest('header', 'format.header');
    });
    Route::get('/instant_order_seller',function(){
        $queries = Input::all();
        $instantModel = new InstantOrder();
        if(Auth::check()){
            $user = Auth::getUser();
            $userID =$user['user_id'];

        }

        $instants = $instantModel->seller($queries,$userID);
        //foreach($instants as $instant){print_r($instant);}exit;
        $states = Config::get('state.data');


        return View::make('instantOrder.order_seller', array('instants' => $instants, 'queries' => $queries,'states' => $states,'userID'=>$userID))->nest('top','format.top')->nest('header', 'format.header');
    });
    Route::get('/order_court_manage',function(){
        if(Auth::check()){
            $instantModel = new InstantOrder();
            $user = Auth::getUser();
            $userID =$user['user_id'];
            $hallID = $_GET['hall_id'];
            $courtID = $_GET['court_id'];
            $halls = $user->Halls;
            if(!$hallID&&count($halls) > 0){
                $hallID = $halls[0]->id;
            }
            //$courtModel = new Court();
            //$courtIDs = $courtModel->where('hall_id','=',$hallID);
             $courts = Court::where('hall_id', '=', $hallID)->get();

            if(!$courtID&&count($courts) > 0){
                $courtID = $courts[0]->id;
            }

            $states = Config::get('state.data');
            $instants = $instantModel->where('hall_id', '=', $hallID)->where('court_id', '=', $courtID)->where('event_date','>=',date('Y-m-d'))->get();
            $dates =array(date('Y-m-d 00:00:00'),date('Y-m-d 00:00:00',strtotime('+1 day')),date('Y-m-d 00:00:00',strtotime('+2 day')),date('Y-m-d 00:00:00',strtotime('+3 day')),date('Y-m-d 00:00:00',strtotime('+4 day')));
           //print_r($instants);exit;
        return View::make('instantOrder.order_court_manage', array('instants' => $instants,'states' => $states,'courts'=>$courts,'halls'=>$halls,'dates'=>$dates))->nest('top','format.top')->nest('header', 'format.header');
    }
    });

    Route::get('/fsm-operate/{id?}/{operate?}', function($id, $operate){
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $fsm->apply($operate);
        $url =  URL::previous();
        return $redirect = Redirect::to($url);
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

Route::get('/testtt', function(){
    return View::make('instant.order.test');
    }
);