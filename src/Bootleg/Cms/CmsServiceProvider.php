<?php namespace Bootleg\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//publishes the assets
	    $this->publishes([__DIR__.'/../../../public' => public_path('vendor/bootleg/cms')], 'public');

	    //publish the migrations:
	    $this->publishes([__DIR__.'/../../migrations/' => public_path('../database/migrations')], 'migrations');

	    // TODO: ^^ when we upgrade next - seems this has been fixed:
	    //$this->publishes([__DIR__.'/../../../src//migrations/' => database_path('/migrations')], 'migrations');

	    //publish the config
	    $this->publishes([__DIR__.'/../../config/bootlegcms.php' => config_path('bootlegcms.php')]); //config

	    //Load views
		$this->loadViewsFrom(__DIR__.'/../../views', 'cms');
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
   public function register()
    {
        $this->registerPublish();

        $this->commands('publish');
    }

    private function registerPublish()
    {
        $this->app['publish'] = $this->app->share(function($app)
        {
            return new Bootleg\Cms\Publish;
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
