<?php namespace Bootleg\Cms; 

class BaseController extends \App\Http\Controllers\Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
    public $application;

        
    public function __construct() {
        $this->application = \Application::getApplication();
        view()->share('application', $this->application);
    }
}