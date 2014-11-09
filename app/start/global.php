<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useDailyFiles(storage_path().'/logs/laravel.log');

App::before(function(\Illuminate\Http\Request $request){
    Log::info(sprintf("route-request:%s", Session::getId()),
        array('url' => $request->fullUrl(), 'params' => $request->all()));
});

App::after(function(\Illuminate\Http\Request $request, $response){
    $content = $response->getContent();
    Log::info(sprintf("route-response:%s|%s|%s", Session::getId(), user_id(), app_user_id()),
        array('content' => ($request->ajax() || strlen($content) < 256) ? $content : 'html or more than 256 bytes'));
});

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error(sprintf("exception:%s", Session::getId()), exception_to_array($exception));
    if(Request::ajax()){
        return rest_fail($exception->getMessage(), $code);
    }else if(!debug()){
        Layout::appendBreadCrumbs('回到首页', '/');
        Layout::appendBreadCrumbs('500');
        return Response::view('500');
    }
});

App::missing(function(Exception $exception){
    Layout::appendBreadCrumbs('回到首页', '/');
    Layout::appendBreadCrumbs('404');

    Log::error(sprintf("exception:%s", Session::getId()), exception_to_array($exception));

    return View::make('layout')->nest('content', '404');
});

App::fatal(function(Exception $exception){
    Log::critical(sprintf("exception:%s", Session::getId()), exception_to_array($exception));
});
/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
    return Response::view('maintenance', array(), 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/
require app_path().'/filters.php';


DB::listen(function($sql, $bindings, $time){
    if(Config::get('app.debug')){
        Log::info(sprintf('sql:%s', substr($sql, 0, 1024)), array(
            'bindings' => count($bindings) > 100 ? 'binding is over 100' : $bindings, 'time'=> $time));
    }
});