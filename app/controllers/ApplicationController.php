<?php

class ApplicationController extends CmsController {

    public function __construct() {
        parent::__construct();
    }
    
    public function anySettings(){
        //$application = Application::getApplication();
        //dd($this->application->cms_package);
        $app_settings = $this->application->setting()->get();
        $application_settings = $app_settings->groupBy('section');
        
        $theme = $this->application->theme()->first();
        
        if (Request::ajax()){
            $cont = View::make( $this->application->cms_package.'::application.settings', compact('cont', 'application', 'application_settings', 'theme')) ;
            return($cont);
        }
        else{
            $cont = View::make( $this->application->cms_package.'::application.settings', compact('application', 'application_settings', 'theme') );
            $layout = View::make( 'cms::layouts.master', compact('cont'));
            return($layout);
        }
    }
    
    public function anyUpdate(){
        //TODO.
    }


}