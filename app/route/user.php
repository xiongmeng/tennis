<?php
Route::get('/user/detail/{id}', function($id){
    $user = cache_user($id);

    return View::make('layout')->nest('content', 'user.detail',
        array());
});
