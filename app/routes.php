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
if(@$_SERVER['HTTP_HOST']){
    $applicationurl = ApplicationUrl::getApplicationUrl();
    if (!@($applicationurl->application)) {
        App::abort(404, "No Application found at url");   //chuck 404 - we can't find the app
    }
    
    $application = $applicationurl->application;

    $GLOBALS['applicationurl'] = serialize($applicationurl);
    $GLOBALS['application'] = serialize($application);
    //I don't like doing this but meh. Laravel likes it's 
    //collections serialized for some reason.
}
else{
    $application = '';
    $applicationurl = '';    
}

Route::group(array('prefix'=>@$applicationurl->folder), function () use ($application, $applicationurl) {
//dd(Request::path());
    $languages = array('en','de');
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
    
    
    App::setLocale($locale);
    
    Route::get('/upload', function () {
        return Redirect::action('PagesController@getUpload');
    });

    Route::get(Utils::cmsRoute, array('as' => 'dash', function () {
        return Redirect::action('UsersController@anyDashboard');
    }));

    Route::any(Utils::cmsRoute.'login', array('uses'=>'UsersController@anyLogin'));

    Route::group(array('prefix'=>Utils::cmsRoute, 'before' => 'auth'), function () use ($locale) {
        Route::group(array('prefix'=>$locale), function () {

            Route::any('/', array('uses'=>'UsersController@anyDashboard'));

            Route::controller('content', 'ContentsController');

            Route::controller('template', 'TemplateController');

            Route::controller('application', 'ApplicationController');

            Route::controller('users', 'UsersController');
        });
    });






//TODO: should this be moved elsewhere so pacakge routes can be included here?
//this route handles the whole front end of the site.

    //this doesn't exists in artisan thus we have to if it here.
    if (@$_SERVER['HTTP_HOST']) {

        App::register($application->service_provider);
    }

    

    Route::any('/{slug?}', function ($slug = '/') use ($application, $applicationurl) {
        //we need to render the correct page.


        

        if (is_null($applicationurl->application)) {
            App::abort(404, "No Application found at url");   //chuck 404 - we can't find the app
        }

        if ($applicationurl->folder !== '/') {
            $slug = str_replace($applicationurl->folder, '', $slug);
        }

        if ($slug !== '/') {
            $slug = "/$slug";
        }
        

       
        $content = Content::where('slug', '=', "$slug")
                ->fromApplication()
                ->live()
                ->first();
        //dd($slug);
        if (is_null($content)) {
            App::abort(404, "No content found at url:'$slug'"); //chuck 404 error.. WE HAVE NO SLUG THAT MATCHES WITHIN THIS APP
        }
        $perm = Permission::getPermission('content', $content->id, 'x');


        //we set the theme package incase it wasn't set above for the
        //whole application.
        App::register($content->service_provider);

        //get view file for this page
        if ($content->view) {
            $view = $content->view;
        } else {
            $view = 'default.view';
        }

        //get layout file for this page
        if ($content->layout) {
            $layout = $content->layout;
        } else {
            $layout = 'default.layout';
        }

        //get the package
        if ($content->package) {
            $package = $content->package;
        } else {
            $package = 'cms';
        }
        
        //share these accross everything.
        View::share('content', $content);

        if (Input::has('view')) {
            $view = View::make("$package::".Input::get('view'));
        } else {
            if (Request::ajax()) {
                $view = View::make("$package::$view");
            } else {
                $view = View::make("$package::$layout")->nest('child', "$package::$view");
            }
        }
        //Access-Control-Allow-Origin: http://example.org
        //$response->header('Content-Type', $value);
        return($view);


    })->where('slug', '(.*)');

//    Route::controller('/', 'PageController');
});
