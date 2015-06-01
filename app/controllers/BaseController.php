<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
    public $application;

        
    public function __construct() {
        $this->application = Application::getApplication();
        View::share('application', $this->application);
    }
}