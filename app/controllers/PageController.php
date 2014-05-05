<?php

class PageController extends BaseController {
	
        public function getRoot(){
            //this is here to generate the root url from. TODO: there's probably a better way of doing this.
        }
    
        
        //returns whatever file from the uploads dir.
        public function getUploads($url = ""){
            //TODO: security on this file.
            $filename = base_path() . '/uploads/'. $url;
            $file = File::get($filename);
            $fileData = new \Symfony\Component\HttpFoundation\File\File($filename);
            $response = Response::make($file, 200);
            $response->headers->set('Content-Type', $fileData->getMimeType());
            return($response);
        }
        
        /*
	 * The default controller that ALL forward pointing urls should get routed through.
	 */
	public function missingMethod($parameters = array()){
            
            //$application = Application::getApplication(null, null, false, true);
            //we can't use getApplication here because we need it fromurl
            /*$applicationUrl = ApplicationUrl::with('application')->where('domain','=',"$domain")
                          ->where('folder','LIKE',"$folder")->first();
            $application = $applicationUrl->application;*/
            
            $applicationurl = ApplicationUrl::getApplicationUrl();
            $application = $applicationurl->application;
            
            
            
            if(is_null($applicationurl->application)){
                App::abort(404, "No Application found at url");   //chuck 404 - we can't find the app
            }
            
            $slug = Request::url();
            $slug = Utils::stripProtocol($slug);

            //dd($applicationurl->domain);
            $slug_replace = trim($applicationurl->domain.$applicationurl->folder, "\/ ");
            $slug = str_replace($slug_replace,'',$slug);
            
            if(!$slug){
                $slug = "/";
            }
           
            $content = Content::where('slug', '=', "$slug")
                    ->fromApplication()
                    ->first();
           
            //dd($slug);
            if(is_null($content)){
                App::abort(404, "No content found at url:'$slug'"); //chuck 404 error.. WE HAVE NO SLUG THAT MATCHES WITHIN THIS APP
            }

            //permission check for content item.
            $permission = Permission::checkPermission('content', $content->id, 'front', "You don't have permission to see this page.");
            if($permission !== true){
                return($permission);
            }


            //we set the theme package..

            App::register($content->service_provider);

            //get view file for this page
            if($content->view){
                $view = $content->view;
            }
            else{
                $view = 'default.view';
            }

            //get layout file for this page
            if($content->layout){
                $layout = $content->layout;
            }
            else{
                $layout = 'default.layout';
            }

            //get the package
            if($content->package){
                $package = $content->package;
            }
            else{
                $package = 'cms';
            }
            
            //share these accross everything.
            View::share('content', $content);           
            View::share('application', $application);
            if (Input::has('view')){
                $view = View::make("$package::".Input::get('view'));
            }
            else{
                if (Request::ajax()){
                    $view = View::make("$package::$view");
                }
                else{
                    $view = View::make("$package::$layout")->nest('child', "$package::$view");
                }
            }

            return($view);
	}
        
        


}
