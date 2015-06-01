<?php namespace Bootleg\Themes;

use Illuminate\Support\ServiceProvider;

class ThemesServiceProvider extends ServiceProvider {

	public function __construct($app) {
        parent::__construct($app);

        //REGISTER HOOKS FOR VARIOUS ACTIONS
        \Event::listen('menu.links', function(){
        	return array (
			    	'activePattern'=>"themes/*",
			    	'icon'=>'glyphicon-wrench', 
			    	'title'=>'Themes', 
			    	'location'=>'Bootleg\Themes\ThemesController@anyIndex'
		    );
		}); 
		
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
	 * @return void
	 */
	public function boot()
	{

		

		$this->package('bootleg/themes');
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
