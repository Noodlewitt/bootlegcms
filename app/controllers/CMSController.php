<?php

class CMSController extends BaseController {
    
    public $application;
        
    public function __construct() {
        parent::__construct();
        $this->application = Application::getApplication();
        
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