<?php namespace Bootleg\Cms;

use Application;
use Carbon\Carbon;
use Collective\Html\HtmlServiceProvider;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Permission;
use Zofe\Rapyd\RapydServiceProvider;
use Request;
use Session;

class CmsServiceProvider extends ServiceProvider
{


    public function __construct($app)
    {
        parent::__construct($app);
        require_once __DIR__ . '/../../handlers/ExceptionHandler.php';
    }

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param Router $router
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../../routes.php';
        $this->publishes([__DIR__ . '/../../../public' => public_path('vendor/bootleg/cms')], 'bootleg.cms.public');
        $this->publishes([__DIR__ . '/../../migrations/' => base_path('database/migrations')], 'bootleg.cms.migrations');
        //$this->publishes([__DIR__.'/../../../src//migrations/' => database_path('/migrations')], 'migrations');
        $this->publishes([__DIR__ . '/../../config/bootlegcms.php' => config_path('bootlegcms.php')], 'bootleg.cms.config');
        $this->loadViewsFrom(__DIR__ . '/../../views', 'cms');//Load views

        if (Config::get('bootlegcms.cms_timezone')) Config::set('app.timezone', Config::get('bootlegcms.cms_timezone'));

        $cms_package = Application::getApplication()->cms_package;
        if(!$cms_package) $cms_package = 'cms';

        Event::listen('auth.login', function ($user)
        {
            $user->loggedin_at = Carbon::now();
            $user->save();
        });
        
        Event::listen('router.matched', function ()
        {
            if(Request::has('sid')){
                Session::setId(Request::get('sid'));
                Session::start();
            }
            else {
                Permission::loadUserPermissions();
            }
        });

        //add in some standard dash items..
        /*
        Event::listen('dashboard.items', function() use($cms_package) {
            $user = User::find(\Auth::user()->id);
            return view($cms_package.'::users.dash_item', compact('user'))->render();
        });

        Event::listen('dashboard.items', function() use ($cms_package) {
            return view($cms_package.'::application.dash_item', ['application'=>\Application::getApplication()])->render();
        });
        */

        $all_applications = Application::with('url')->get();

        View::composer(['cms::*', $cms_package.'::*'], function($view) use ($cms_package, $all_applications)
        {
            if(!$view->offsetExists('cms_package')) $view->with('cms_package', $cms_package);
            $view->with('applications', $all_applications);
        });

        //load middleware, helpers, views, routes
        $router->middleware('permissions', 'Bootleg\Cms\Middleware\Permissions');

        //register the command...
        $this->commands('Bootleg\Cms\Publish');
    }

    public function register()
    {
        if (Config::get('bootlegcms.custom_errors') == true)
        {
            $this->app->singleton(
                'Illuminate\Contracts\Debug\ExceptionHandler',
                'Bootleg\Cms\ExceptionHandler'
            );
        }
        $this->app->register(RapydServiceProvider::class);
        $this->app->register(HtmlServiceProvider::class);
        AliasLoader::getInstance()->alias('Form', 'Collective\Html\FormFacade');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Illuminate\Contracts\Debug\ExceptionHandler'];
    }

    public static function getPublishGroups()
    {
        return static::$publishGroups;
    }

}
