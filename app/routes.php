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
//TODO: sort this out a bit - we need to use these globals or get rid.
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
    
    
    App::setLocale($locale);
    
    Route::get('/upload', function () {
        return Redirect::action('PagesController@getUpload');
    });

    Route::get(Utils::cmsRoute, array('as' => 'dash', function () {
        return Redirect::action('UsersController@anyDashboard');
    }));

    Route::any(Utils::cmsRoute.'login', array('uses'=>'UsersController@anyLogin'));

    Route::group(array('prefix'=>Utils::cmsRoute), function () use ($locale) {

        Route::group(array('prefix'=>$locale), function () {


            Route::any('/', array('uses'=>'UsersController@anyDashboard'));

            Route::controller('content', 'ContentsController');

            Route::controller('template', 'TemplateController');

            Route::controller('application', 'ApplicationController');

            Route::controller('users', 'UsersController');
            
            Route::controller('reminders', 'RemindersController');
        });
    });
    

    Route::get('uploads/{filename}', function($filename = null){
        //TODO: security on this file.
        $filename = stripslashes(str_replace('/','',$filename));
        
        $filename = storage_path() . '/uploads/'. $filename;
        $file = File::get($filename);
        $fileData = new \Symfony\Component\HttpFoundation\File\File($filename);
        $response = Response::make($file, 200);
        $response->headers->set('Content-Type', $fileData->getMimeType());
        return($response);
    });



    Route::any('/{slug?}', function ($slug = '/') use ($application, $applicationurl) {
        //TODO: we should really move this into PageController at some point.

        //dd($slug);
        $pathInfo = pathinfo($slug);
        
        if($slug != '/'){
            $slug = $pathInfo['dirname']."/".$pathInfo['filename'];
        $slug = str_replace('./', '', $slug);    
        }
        
        $extension = @$pathInfo['extension'];

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
                ->with('setting')
                ->first();
        //dd($slug);
        if (is_null($content)) {
            App::abort(404, "No content found at url:'$slug'"); //chuck 404 error.. WE HAVE NO SLUG THAT MATCHES WITHIN THIS APP
        }
        //$perm = Permission::getPermission('content', $content->id, 'x');

        //we set the theme package incase it wasn't set above for the
        //whole application.
        //dd($content->service_provider);
//        App::register($content->service_provider);

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
        
        if($extension == 'json'){
            $view = Response::json($content);
        }
        else{
            if (Input::has('view')) {
                $view = View::make("$package::".Input::get('view'));
            } else {
                if (Request::ajax()) {
                    $view = View::make("$package::$view");
                } else {
                    $view = View::make("$package::$layout")->nest('child', "$package::$view");
                }
            }    
        }
        
        //Access-Control-Allow-Origin: http://example.org
        //$response->header('Content-Type', $value);
        return($view);

    })->where('slug', '(.*)');
    //});

//    Route::controller('/', 'PageController');
});
