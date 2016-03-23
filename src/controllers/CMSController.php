<?php namespace Bootleg\Cms;

class CMSController extends BaseController {


    public function __construct()
    {
        parent::__construct();

        $this->middleware('permissions', ['except' => [
            'anyLogin', 'getForgotPassword', 'postForgotPassword', 'getResetPassword', 'postResetPassword'
        ]]);
    }

    /**
     * Allows for easier view handling by checking for view existance with fallback to defaults
     *
     * @param  string $view [the view to be rendered]
     * @param  array $params [any params that need to be sent to the view]
     * @param  string $package [(optional) the package if required (for packages)]
     */
    public function render($view, $params = [], $package = null)
    {

        view()->share('cms_package', ($package ? $package : $this->application->cms_package));

        $view_path = $this->getCmsViewPath($view, $package);

        return view($view_path, $params);
    }

    public function getCmsViewPath($view, $package = null)
    {
        if ($package && view()->exists($package . '::' . $view)) return $package . '::' . $view;

        if (view()->exists($this->application->cms_package . '::' . $view)) return $this->application->cms_package . '::' . $view;

        return config('bootlegcms.cms_hint_path') . '::' . $view;
    }
}
