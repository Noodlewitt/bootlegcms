<?php namespace Bootleg\Cms;

class ApplicationController extends CmsController
{

    public $content_mode = 'application';
    
    public function __construct()
    {
        parent::__construct();
        view()->share('content_mode', $this->content_mode);
    }
    
    public function anyIndex()
    {
        $applications = $this->application->descendantsAndSelf()->paginate(15);
        return $this->render('application.index', compact('applications')) ;
    }

    /**
     * render children
     * @return [type] [description]
     */
    public function anyChildren()
    {
        $applications = $this->application->descendantsAndSelf()->paginate(15);
        return $this->render('application.index', compact('applications')) ;
    }

    public function anyCreate()
    {
        $newApp = new \Application();
        return $this->render('application.create', ['newApp' => $newApp]) ;
    }

    public function deleteDestroy($id)
    {
        $application = \Application::findOrFail($id);
        $application->delete();
    }

    /**
     * Small preview of application
     * @param  [type] $id [description]
     * @return [type]     [description]
    */
    public function getView($id)
    {
        $application = \Application::findOrFail($id);
        return $this->render('application.view', ['application' => $application]) ;
    }
    
    public function postStore()
    {
        $input = \Input::all();
        if ($input['parent_id']) {
            $parentApplication = \Application::find($input['parent_id']);
        } else {
            $parentApplication = $this->application;
        }


        $validation = \Validator::make($input, \Application::$rules);
       // dd($input);
        if ($validation->passes()) {
            $newApp = new \Application();
            $newApp->name = $input['name'];
            $newApp->user_id = \Auth::user()->id;
            //$newApp->parent_id = $parentApplication->id;
            $newApp->cms_package = $parentApplication->cms_package;
            

            
            $parentApplication->children()->save($newApp);

            //TODO: Fix baum here - not saving correctly
            \Application::rebuild();
            
            //we need to do the urls..
            $domains = explode(',', $input['domain']);
            $appUrls = [];
            foreach ($domains as $domain) {
                $appUrl = new \ApplicationUrl();
                $appUrl->domain = $domain;
                $appUrl->folder = '/'; //TODO: folders - is this ever going to work?
                $appUrls[] = $appUrl;
            }
            $newApp->url()->saveMany($appUrls);

            //and the plugins
            $parentPlugins = $parentApplication->plugins()->get();
            foreach ($parentPlugins as $parentPlugin) {
                $newApp->plugins()->attach($parentPlugin->id);//associate($parentPlugin)
            }

            return redirect()->action('\Bootleg\Cms\ApplicationController@anyIndex')->with(['success'=>'Application Succesfully Created']);
        }
        \Request::flash();
        return redirect()->back()->withErrors($validation->errors());
    }
    
    public function anySettings()
    {

        //$application = Application::getApplication();
        //dd($this->application->cms_package);
        $app_settings = $this->application->setting()->get();
        $application_settings = $app_settings->groupBy('section');
        
        $plugins = $this->application->plugins()->first();
        
        return $this->render('application.settings', compact('cont', 'application', 'application_settings', 'plugins')) ;
    }
    
    /**
     * Sets language of back end
     **/
    public function anySetlang()
    {
    }
    
    public function anyUpdate()
    {

        $input = array_except(\Input::all(), '_method');

        $validation = \Validator::make($input, \Application::rules($this->application->id));

        if ($validation->passes()) {

            $this->application->update($input);
            //dd($input);
            $domains = $input['domains'];
            //remove all the domains currently on the application:
            foreach ($this->application->url as $url) {
                $url->delete();
            }
            $appUrls = [];

            foreach ($domains as $domain) {
                $appUrl = new \ApplicationUrl();
                $appUrl->domain = $domain;
                $appUrl->folder = '/'; //TODO: folders?
                $appUrls[] = $appUrl;
            }
            $this->application->url()->saveMany($appUrls);

            if (@$input['setting']) {
                foreach ($input['setting'] as $type=>$setGrp) {

                    foreach ($setGrp as $key=>$setting) {
                        foreach($setting as $settingId=>$set){
                            $applicationSetting = \ApplicationSetting::withTrashed()->find($settingId);
                            $applicationSetting->name = $type;
                            $applicationSetting->value = $set;
                            $applicationSetting->application_id = $this->application->id;
                            $applicationSetting->field_type = $applicationSetting->field_type?$applicationSetting->field_type:'text';

                            $applicationSetting->save();
                            $applicationSetting->restore();
                        }                        
                    }
                }
            }
            return redirect()->action('\Bootleg\Cms\ApplicationController@anySettings')->with('success', 'Settings Updated');
        }
        else{
            dd($input,$validation->errors()->all());
        }
    }
}
