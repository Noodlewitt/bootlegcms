<?php namespace Bootleg\Cms;

class CmsErrorServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function __construct($app) {
        require_once __DIR__.'/../../handlers/ExceptionHandler.php';
    }
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;
    /**
     * Register the service provider.
     */
    public function register()
    {
        \App::singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            'Bootleg\Cms\ExceptionHandler'
        );
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