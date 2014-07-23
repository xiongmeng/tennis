<?php

Route::group(array('prefix' => 'fj'), function(){

    Route::get('/', function(){
        return 'I am fj';
    });

});