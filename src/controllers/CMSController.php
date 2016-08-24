<?php namespace Bootleg\Cms; 

class CMSController extends BaseController {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this->middleware('permissions', array('except'=>'anyLogin'));

        //dd(\URL::current());

    //    $this->beforeFilter('permission:'.\Route::currentRouteAction().','.$string, array('except'=>'anyLogin'));
        //
        $this->applications = \Application::with('url')->get();
        view()->share('applications', $this->applications);       
        //we need to register the package we are using:
    }

    /**
     * Allows for easier view handling by checking for view existance with fallback to defaults
     * @param  string $view    [the view to be rendered]
     * @param  array  $params  [any params that need to be sent to the view]
     * @param  string $package [(optional) the package if required (for packages)]
     */
    public function render($view, $params = array(), $package=''){

        view()->share('cms_package', ($package?$package:$this->application->cms_package));
        //if we are given a package - try to render that..
        if ($package) {
            if(view()->exists("$package::$view")) {
                view()->share('cms_package', $package);
                return view("$package::$view", $params);
            }
        }

        //if there's no package, we take the package from the application table
        if (view()->exists($this->application->cms_package.'::'.$view)){
            
            return view($this->application->cms_package.'::'.$view, $params);
        }
        
        //a last ditch attempt to render something.
        return view(config('bootlegcms.cms_hint_path').'::'.$view, $params);
    }
}