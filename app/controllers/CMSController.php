<?php

class CMSController extends BaseController {
    
    
    
    public function __construct() {
        parent::__construct();
        
        //figure out permissions:
        $action = str_replace('/index','',action(Route::currentRouteAction()));
        $string = (str_replace($action,'',URL::current()));
        $string = trim($string, '/');
        $this->beforeFilter('permission:'.Route::currentRouteAction().','.$string, array('except'=>'anyLogin'));
        //
        
        $this->applications = Application::with('url')->get();
        View::share('applications', $this->applications);       
        //we need to register the package we are using:
    }

    /**
     * Allows for easier view handling by checking for view existance with fallback to defaults
     * @return [type] [description]
     */
    public function render($view, $params = array(), $package=''){
        if (View::exists($this->application->cms_package.'::'.$view)){
            $view = $this->application->cms_package . "::" . $view;
            View::share('cms_package', $package?$package:$this->application->cms_package);              
        }
        else{
            $view = Utils::cmsHintPath . "::" . $view;
            View::share('cms_package', $package?$package:Utils::cmsHintPath);  
        }
        return View::make($view, $params);
    }
}