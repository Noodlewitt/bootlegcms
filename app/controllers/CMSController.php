<?php

class CMSController extends BaseController {
    
    public $application;
    
    protected $layout = 'cms::layouts.master';
    
    public function __construct() {
        parent::__construct();
        
        //figure out permissions:
        $action = str_replace('/index','',action(Route::currentRouteAction()));
        $string = (str_replace($action,'',URL::current()));
        $string = trim($string, '/');
        $this->beforeFilter('permission:'.Route::currentRouteAction().','.$string, array('except'=>'anyLogin'));
        //
        
        //todo:tidy up this vv
        $this->application = Application::getApplication();
                        
        $application = Application::getApplication();
        View::share('application', $application);
        
        $applications = Application::with('url')->get();
        View::share('applications', $applications);       
        //we need to register the package we are using:
        App::register($this->application->cms_service_provider);
    }
}