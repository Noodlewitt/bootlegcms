<?php namespace Bootleg\Cms;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
/**
 * Since we don't want to register the service providers for plugins globally, we cant use
 * the normal vendor:publush command (since it only looks in app for SPs). Using this we can 
 * check the db for any plugins and run an seet publish off that.
 */
class Publish extends \Illuminate\Console\Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bootleg:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register plugin assets';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        //echo $this->argument('example');
        //echo $this->option('example');
        if($this->option('app_name')){
            $application = \Application::with('plugins')->where('name',$this->option('app_name'))->get();
        }
        else if($this->option('app_id')){
            $application = \Application::with('plugins')->where('id',$this->option('app_id'))->get();
        }
        else{
            $application = \Application::with('plugins')->get();    
        }
        echo("\n");
        foreach($application as $app){
            foreach($app->plugins as $plugin){
                //Register appliation service providers
                \App::register($plugin->service_provider);
                echo("Publishing for ".$plugin->name."\n");
            }    
        }
        //we now need to re asset publish?
        //TODO: do we really want to force this 100% of the time?
        \Artisan::call('vendor:publish', ['--force'=>1]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            //none.
            //['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        //TODO: maybe could use options to publish specific packages?
        return [
            ['app_name', null, InputOption::VALUE_OPTIONAL, 'The name of the app we want to publish assets for.', null],
            ['app_id', null, InputOption::VALUE_OPTIONAL, 'The ID of the app we want to publish assets for.', null],
        ];
    }

}
