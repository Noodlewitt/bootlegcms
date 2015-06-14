<?php namespace Bootleg\Cms; 

class PageController extends BaseController
{

    public function getRoot()
    {
        //this is here to generate the root url from. TODO: there's probably a better way of doing this.
    }


    //returns whatever file from the uploads dir.
    public function getUploads ($url = "")
    {
        dd($url);
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


    public static function page($slug, $application, $applicationurl){
        $pathInfo = pathinfo($slug);
        if($pathInfo['dirname'] == '/'){
            $pathInfo['dirname'] = '';
        }
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

        //final bit of tweaking..
        $slug = '/'.trim($slug, '/');
        
        $content = \Content::where('slug', '=', "$slug")
                ->fromApplication()
                ->live()
                ->with('setting')
                ->first();
        //dd($slug);
        if (is_null($content)) {
             \App::abort(404, "No content found at url:'$slug'"); //chuck 404 error.. WE HAVE NO SLUG THAT MATCHES WITHIN THIS APP
        }
        //$perm = Permission::getPermission('content', $content->id, 'x');

        //we set the theme package incase it wasn't set above for the
        //whole application.
        //dd($content->service_provider);


        //get view file for this page
        if ($content->view) {
            $view = $content->view;
        } else {
            $view = 'default.view';
        }

        //get the package
        if ($content->package) {
            $package = $content->package;
        } else {
            $package = 'cms';
        }

        //share these accross everything.
        view()->share('content', $content);

        if($extension == 'json'){
            $view = response()->json($content);
        }
        else{
            $view = view("$package::$view");
        }

        //Next wee need to organise some headers for us.
        if($content->headers){
            $headers = (array) json_decode($content->headers);
            $code = @$headers['Response'];
        }
        else{
            $code = 200;
        }

        $response = response($view);

        if(@$headers){
            foreach($headers as $key=>$header){
                if($key != 'Response'){
                    $response->header($key, $header);
                }
            }
        }


        return $response;
    }
}
