<?php
Route::post('/upload', function(){
    $file = Input::file('qqfile');
    $destination = public_path() . '/uploadfiles/tmp/';
    $file->move($destination, $file->getClientOriginalName());
    return rest_success(array('url' => '/uploadfiles/tmp/' . $file->getClientOriginalName()));
});

