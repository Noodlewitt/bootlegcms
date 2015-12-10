<?php namespace Bootleg\Cms;

use Carbon\Carbon;
use Collective\Html\HtmlServiceProvider;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zofe\Rapyd\RapydServiceProvider;

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

        Event::listen('auth.login', function ($user)
        {
            $user->loggedin_at = Carbon::now();
            $user->save();
        });
        Event::listen('router.matched', function ()
        {
            if (Auth::user()) $this->loadUserPermissions();
        });

        $cms_package = \Application::getApplication()->cms_package;
        if(!$cms_package) $cms_package = 'cms';

        View::composer($cms_package.'::*', function($view) use ($cms_package)
        {
            if(!$view->offsetExists('cms_package')) $view->with('cms_package', $cms_package);
            $view->with('applications', \Application::with('url')->get());
        });

        //load middleware, helpers, views, routes
        $router->middleware('permissions', 'Bootleg\Cms\Middleware\Permissions');
        $router->middleware('cms.setup', 'Bootleg\Cms\Middleware\CmsSetup');

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

    public function loadUserPermissions()
    {
        if(Auth::user()->relations['permissions'] === null)
        {
            $permissions = \Permission::where(function ($query)
            {
                $query->where(function ($query)
                {
                    $query->where(function ($query)
                    {
                        $query->where('requestor_id', '=', Auth::user()->id)
                            ->orWhere('requestor_id', '=', '*');
                    })
                        ->where('requestor_type', '=', 'user');
                })
                    ->orWhere(function ($query)
                    {    //where role
                        $query->where(function ($query)
                        {
                            $query->where('requestor_id', '=', Auth::user()->role_id)
                                ->orWhere('requestor_id', '=', '*');
                        })
                            ->where('requestor_type', '=', 'role');
                    });
            })
                ->where(function ($query)
                {
                    $app_id = \Application::getApplication()->id;
                    $query->where('application_id', $app_id)
                        ->orWhere('application_id', '*');
                })->get();

            Auth::user()->setRelation('permissions', $permissions);
        }
    }

}
