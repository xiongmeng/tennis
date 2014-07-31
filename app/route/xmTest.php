<?php

Route::group(array('prefix' => 'xm'), function(){

    Route::get('/', function(){
        $user = User::take(10)->offset(10)->where('privilege', '=', 2)->where('prestore_fee', '>', 200)->get();
        return count($user);
    });


    Route::get('/page', function(){
        $users = User::where(function(\Illuminate\Database\Eloquent\Builder $query){
            if(Input::get('nickname')){
                $query->where('nickname', 'like', '%' . Input::get('nickname') . '%');
            }
            if(Input::get('telephone')){
                $query->where('telephone', 'like', Input::get('telephone'));
            }
        })
       ->paginate(2);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users));
    });

    Route::get('/search', function(){
        $queries = Input::all();

        $userModel = new User();
        $users = $userModel->search($queries);

        return View::make('xm.layout')->nest('content', 'xm.user.profile', array('users' => $users, 'queries' => $queries));
    });

    Route::get('/sport', function(){
        return \Sports\Constant\Finance::ACCOUNT_BALANCE;
    });

    Route::get('/user-role', function(){
        $roles = User::find(888928)->roles;
        return View::make('xm.user.user-role', array('roles' => $roles));
    });

    Route::get('/role-header', function(){
        $headers = Role::find(1)->headers;
        return View::make('xm.user.role-header', array('headers' => $headers));
    });

    Route::get('/header-header', function(){
        $headers = Header::find(0)->children;
        return View::make('xm.user.header-header', array('headers' => $headers));
    });

    Route::get('/acl-cfg', function(){
        $roles = user::find(888928)->roles;

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
        $data = array('headers' => $headers, 'acl' => $acl);
        return $view = View::make('home')->nest('top','format.top')->nest('header', 'format.header',array('headers' => $headers, 'acl' => $acl) );
    });

    Route::get('/fsm-init', function(){
        $instantOrder = InstantOrder::create(array());
        $fsm = new InstantOrderFsm($instantOrder);
        echo $fsm->getCurrentState();
    });

    Route::get('/fsm-operate/{id?}/{operate?}', function($id, $operate){
        $instantOrder = InstantOrder::findOrFail($id);
        $fsm = new InstantOrderFsm($instantOrder);
        $previousState = $fsm->getCurrentState();

        $fsm->apply($operate);

        echo "<br/>";
        echo $previousState;
        echo '->';
        echo $fsm->getCurrentState();
    });

    Route::get('/artisan', function(){
        Artisan::call('instantOrder:generate', array('--date' => array('2014-07-30')),
            new \Symfony\Component\Console\Output\StreamOutput(fopen(storage_path() . '/logs/artisan.log', 'w')));
    });

    Route::get('/register', function(){
//        User::create(array('nickname' => 'hall8888', 'password' =>Hash::make('123456')));
        $res = Auth::validate(array('nickname' => 'hall8888', 'password' => '123456'));
        echo $res;
    });
});