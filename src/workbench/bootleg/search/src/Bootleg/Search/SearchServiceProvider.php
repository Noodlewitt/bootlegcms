<?php namespace Bootleg\Search;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	public function __construct($app) {
        parent::__construct($app);

		\Event::listen('routes.before', function() {
            include __DIR__.'/../../routes.php';
        });
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('bootleg/search');
//		\App::register('Mmanos\Search\SearchServiceProvider');


//		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
//		$aliases = \Config::get('app.aliases');
		//Alias the Sentry package
//		if (empty($aliases['Search'])) {
//			$loader->alias('Search', 'Mmanos\Search\Facade');
//		}


		
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
