<?php

class PageController extends BaseController
{

    public function getRoot()
    {
        //this is here to generate the root url from. TODO: there's probably a better way of doing this.
    }

    
    //returns whatever file from the uploads dir.
    public function getUploads ($url = "")
    {
        //TODO: security on this file.
        $filename = base_path() . '/uploads/'. $url;
        $file = File::get($filename);
        $fileData = new \Symfony\Component\HttpFoundation\File\File($filename);
        $response = Response::make($file, 200);
        $response->headers->set('Content-Type', $fileData->getMimeType());
        return($response);
    }
        
	/*
	 *Sets language for front end pages.
	 **/
	public function getLanguage($language){
		$language = '';
	}


    public function page($slug){
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
    }
}
