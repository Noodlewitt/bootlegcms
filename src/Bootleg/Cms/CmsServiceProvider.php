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

	    //load translations..
	    $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'cms');

	    //Load views
		$this->loadViewsFrom(__DIR__.'/../../views', 'cms');
		include __DIR__.'/../../routes.php';

		//register the command...
		$this->commands('Bootleg\Cms\Publish');
	}



   	public function register(){
        
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
