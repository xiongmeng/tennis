<?php
Route::post('/upload', function(){
    $file = Input::file('qqfile');
    $destination = public_path() . '/uploadfiles';
    $file->move($destination, $file->getClientOriginalName());
    return rest_success(array('url' => '/uploadfiles/' . $file->getClientOriginalName()));
});