<?php

class ApplicationController extends CmsController {

    public function __construct() {
        parent::__construct();
    }
    
    public function anySettings(){
        //$application = Application::getApplication();
        //dd($this->application->cms_package);
        $application_settings = $this->application->setting()->get();
        $theme = $this->application->theme()->first();
        return View::make($this->application->cms_package.'::application.settings', array('application'=>$this->application, 'application_settings'=>$application_settings, 'theme'=>$theme));
    }
    
    public function anyUpdate(){
        //TODO.
    }


}