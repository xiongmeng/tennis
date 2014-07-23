<?php

Route::group(array('prefix' => 'xm'), function(){

    Route::get('/', function(){
        return 'I am xm';
    });

});