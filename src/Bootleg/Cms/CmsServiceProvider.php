<?php namespace Bootleg\Cms;

use Carbon\Carbon;
use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

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

	    //Load views
		$this->loadViewsFrom(__DIR__.'/../../views', 'cms');
		include __DIR__.'/../../routes.php';
		include __DIR__.'/../../components/helpers.php';

		//load middleware, helpers, views, routes
		$router->middleware('permissions', 'Bootleg\Cms\Middleware\Permissions');

		//register the command...
		$this->commands('Bootleg\Cms\Publish');

		$this->app->events->listen('auth.login', function ($user)
		{
			$user->loggedin_at = Carbon::now();
			$user->save();
		});
	}



    public function register()
    {
		if(config('bootlegcms.custom_errors') == true){
			$this->app->singleton(
					'Illuminate\Contracts\Debug\ExceptionHandler',
					'Bootleg\Cms\ExceptionHandler'
			);
		}

		$additional_services = [
			'\Collective\Html\HtmlServiceProvider',
		];

		foreach($additional_services as $service){
			if(class_exists($service)) $this->app->register($service);
		}

		$additional_facades = [
			'Form' => '\Collective\Html\FormFacade',
			'Html' => '\Collective\Html\HtmlFacade',
		];

		foreach($additional_facades as $facade => $class){
			if(class_exists($class)) AliasLoader::getInstance()->alias($facade, $class);
		}
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
