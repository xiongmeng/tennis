<?php

Route::group(array('prefix' => 'fj'), function(){

    Route::get('/', function(){
        return 'I am fj';
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