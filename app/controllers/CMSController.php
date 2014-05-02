<?php

class CMSController extends BaseController {
    
    public $application;
    
    protected $layout = 'cms::layouts.master';
    
    public function __construct() {
        parent::__construct();
        
        //todo:tidy up this vv
        $this->application = Application::getApplication();
                        
        $application = Application::getApplication();
        View::share('application', $application);
        $applications = Application::get();
        View::share('applications', $applications);       
        //we need to register the package we are using:
        App::register($this->application->cms_service_provider);
    }
    
    public function ajaxRender($view){
        
        if (Request::ajax()){
            return($view);
        }
        else{
            return($view);
            $this->layout->content = $view;
        }
    }
}