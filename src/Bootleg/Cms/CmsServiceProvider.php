<?php namespace Bootleg\Cms;

use Collective\Html\HtmlServiceProvider;
use Config;
use Illuminate\Support\ServiceProvider;
use Zofe\Rapyd\RapydServiceProvider;
use Illuminate\Routing\Router;

class CmsServiceProvider extends ServiceProvider {


    public function __construct($app) {
        parent::__construct($app);
        require_once __DIR__.'/../../handlers/ExceptionHandler.php';
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
		//publishes the assets
	    $this->publishes([__DIR__.'/../../../public' => public_path('vendor/bootleg/cms')], 'public');

	    //publish the migrations:
	    $this->publishes([__DIR__.'/../../migrations/' => base_path('database/migrations')], 'migrations');

	    // TODO: ^^ when we upgrade next - seems this has been fixed:
	    //$this->publishes([__DIR__.'/../../../src//migrations/' => database_path('/migrations')], 'migrations');

	    //publish the config
	    $this->publishes([__DIR__.'/../../config/bootlegcms.php' => config_path('bootlegcms.php')]); //config

        if(Config::get('bootlegcms.cms_timezone')) Config::set('app.timezone', Config::get('bootlegcms.cms_timezone'));
	    //Load views
		$this->loadViewsFrom(__DIR__.'/../../views', 'cms');
		include __DIR__.'/../../routes.php';

        //load middleware, helpers, views, routes
        $router->middleware('permissions', 'Bootleg\Cms\Middleware\Permissions');
        $router->middleware('cms.setup', 'Bootleg\Cms\Middleware\CmsSetup');

		//register the command...
		$this->commands('Bootleg\Cms\Publish');
	}



    public function register()
    {
		if(Config::get('bootlegcms.custom_errors') == true){
			$this->app->singleton(
					'Illuminate\Contracts\Debug\ExceptionHandler',
					'Bootleg\Cms\ExceptionHandler'
			);
		}
        $this->app->register(RapydServiceProvider::class);
        $this->app->register(HtmlServiceProvider::class);


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

}
