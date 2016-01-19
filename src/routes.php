<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['middleware' => 'cms.setup'], function () {
    if(env('APP_DEBUG')){
        ini_set('xdebug.var_display_max_depth', -1);
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
    }

    if(@$_SERVER['HTTP_HOST']){
        $applicationurl = \ApplicationUrl::getApplicationUrl();
        $application = @$applicationurl->application;

        if (!$application) {
            App::abort(404, "No Application found at url"); //chuck 404 - we can't find the app
        }
    } else {
        $applicationurl = \ApplicationUrl::first();
        $application = $applicationurl->application;
    }

    $GLOBALS['applicationurl'] = serialize($applicationurl);
    $GLOBALS['application'] = serialize($application);

    $prefix = @$applicationurl->folder ? $applicationurl->folder : '/';
    if($applicationurl->prefix) $prefix .= $applicationurl->prefix;

    Route::group(array('prefix'=>$prefix), function () use ($application, $applicationurl) {
    //dd(Request::path());
        $languages = array('en'); //TODO <<
        $locale = null;


        //we need to hunt down the right bit of the url to use for language.
        $pathArr = explode('/', Request::path());
        foreach ($pathArr as $segment) {
            if (in_array($segment, $languages)) {

                //this is our language!
                $locale = $segment;
                break;
            }
        }
        //this doesn't exists in artisan thus we have to if it here.

        if (@$_SERVER['HTTP_HOST']) {
            foreach($application->plugins as $plugin){
                App::register($plugin->service_provider);
            }
        }

        Event::fire('routes.before');

        App::setLocale($locale);

        Route::get('/upload', function () {
            return Redirect::action('Bootleg\Cms\PagesController@getUpload');
        });

        Route::get(config('bootlegcms.cms_route'), array('as' => 'dash', function () {
            return Redirect::action('Bootleg\Cms\UsersController@anyDashboard');
        }));

        Route::any(config('bootlegcms.cms_route').'login', array('uses'=>'Bootleg\Cms\UsersController@anyLogin'));

        Route::group(array('prefix'=>config('bootlegcms.cms_route')), function () use ($locale) {

            Route::group(array('prefix'=>$locale), function () {


                Route::any('/', array('uses'=>'Bootleg\Cms\UsersController@anyDashboard'));

                Route::controller('content', 'Bootleg\Cms\ContentsController');

                Route::controller('template', 'Bootleg\Cms\TemplateController');

                Route::controller('application', 'Bootleg\Cms\ApplicationController');

                Route::controller('users', 'Bootleg\Cms\UsersController');

                Route::controller('reminders', 'Bootleg\Cms\RemindersController');
            });
        });

        Route::pattern('upl', '(.*)');
        Route::get('/uploads/{upl?}', function($filename = null){
            //TODO: security on this file.

            //$filename = stripslashes(str_replace('/','',$filename));

            $filename = storage_path() . '/uploads/'. $filename;
            $file = File::get($filename);
            $fileData = new \Symfony\Component\HttpFoundation\File\File($filename);
            $response = Response::make($file, 200);
            $response->headers->set('Content-Type', $fileData->getMimeType());
            return($response);
        });


        Route::any('/{slug?}', function ($slug = '/') use ($application, $applicationurl) {
            //TODO: we should really move this into PageController at some point.
            return Bootleg\Cms\PageController::page($slug, $application, $applicationurl);
        })->where('slug', '(.*)');
        //});

        \Event::fire('routes.after');
    //    Route::controller('/', 'PageController');
    });
});
