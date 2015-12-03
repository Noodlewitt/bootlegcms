<?php namespace Bootleg\Cms;

use App;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Plugin;
use Symfony\Component\Console\Input\InputOption;

/**
 * Since we don't want to register the service providers for plugins globally, we cant use
 * the normal vendor:publush command (since it only looks in app for SPs). Using this we can
 * check the db for any plugins and run an seet publish off that.
 */
class Publish extends Command
{

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
        $app_name = $this->option('app_name');
        $app_id = $this->option('app_id');
        $plugins = new Plugin;

        if ($app_name || $app_id)
        {
            $plugins = $plugins->whereHas('applications', function ($q) use ($app_name, $app_id)
            {
                if ($app_name) $q->where('name', $app_name);
                if ($app_id) $q->where('id', $app_id);
            });
        }
        $plugins = $plugins->get();

        foreach ($plugins as $plugin)
        {
            //Register application service providers
            app()->register($plugin->service_provider);
            echo("Registered plugin " . $plugin->name . "\n");
        }

        //publish all assets, without overwriting
        echo("Publishing files... \n");
        $this->call('vendor:publish');

        echo("Publishing assets... \n");

        $groups = CmsServiceProvider::getPublishGroups();

        foreach($groups as $tag => $assets)
        {
            //force publish all assets with a tag ending in 'public'
            if(ends_with($tag, 'public')){
                $this->call('vendor:publish', [
                    '--tag'      => $tag,
                    '--force'    => 1,
                ]);
            }
        }
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
