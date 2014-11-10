<?php
Route::get('/area/cities/{province}', function ($province) {
    return rest_success(Area::cities($province));
});

Route::get('/area/counties/{city}', function ($city) {
    return rest_success(Area::counties($city));
});