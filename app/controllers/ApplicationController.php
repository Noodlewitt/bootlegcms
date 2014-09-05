<?php

class ApplicationController extends CmsController {

    public function __construct() {
        parent::__construct();
    }
    
    public function anyCreate(){
        $themes = Theme::lists('name','id');
        if (Request::ajax()){
            $cont = View::make( $this->application->cms_package.'::application.create', compact('cont', 'application', 'themes')) ;
            return($cont);
        }
        else{
            $cont = View::make( $this->application->cms_package.'::application.create', compact('application','themes') );
            $layout = View::make( 'cms::layouts.master', compact('cont'));
            return($layout);
        }
    }
    
    public function postStore(){
     /*   
        $root1 = Content::create(array('name' => 'R1', 'application_id' => 1));
        $root2 = Content::create(array('name' => 'R2', 'application_id' => 2));

        $child1 = Content::create(array('name' => 'C1', 'application_id' => 1));
        $child2 = Content::create(array('name' => 'C2', 'application_id' => 2));

        $child1->makeChildOf($root1);
        $child2->makeChildOf($root2);
        var_dump(Content::isValid());
        exit();*/
        
        $currentApplication = Application::getApplication();
        $input = Input::all();

        $validation = Validator::make($input, Application::$rules);
       // dd($input);
        if ($validation->passes()) {
            $themeApp = Application::find($input['theme']);
            $newApp = new Application();
            $newApp->theme_id = $themeApp->id;
            $newApp->name = $input['name'];
            $newApp->parent_id = $currentApplication->id;
            $newApp->cms_theme_id = $currentApplication->cms_theme_id;
            $newApp->cms_package = $currentApplication->cms_package;
            $newApp->cms_service_provider = $currentApplication->cms_service_provider;
            $newApp->package = $themeApp->package;
            $newApp->service_provider = $themeApp->service_provider;
            
            //we need to do the urls..
            $urls = explode(',', $input['domain']);



            if ($newApp->save()) {
                foreach ($urls as $url) {
                    $appUrl = new ApplicationUrl(array(
                        'domain'=>trim($url, ' /'),
                        'folder'=>'/'
                        ));
                    $newApp->url()->save($appUrl);
                }

                //we now need to start duplicating an existing application
                //grab the theme app as a hierachy so we can start recursivly duping.
                //Content::find(2)->makeRoot();
                
                //dd(DB::getQueryLog());
                
                $themeContent = Content::where('application_id','=', $themeApp->id)
                        ->whereNull('parent_id')->first()
                        ->getDescendantsAndSelf()
                        ->toHierarchy();
                

                foreach($themeContent as $tc){
                    Content::doop(true,$tc, null, $newApp->id);
                }
                
            }
            
            dd($input);
            
            //we want to store or update the row.
            $saved = array(Application::create($input));
            
        }
    }
    
    public function anySettings(){
        //$application = Application::getApplication();
        //dd($this->application->cms_package);
        $app_settings = $this->application->setting()->get();
        $application_settings = $app_settings->groupBy('section');
        
        $theme = $this->application->theme()->first();
        
        if (Request::ajax()){
            $cont = View::make( $this->application->cms_package.'::application.settings', compact('cont', 'application', 'application_settings', 'theme')) ;
            return($cont);
        }
        else{
            $cont = View::make( $this->application->cms_package.'::application.settings', compact('application', 'application_settings', 'theme') );
            $layout = View::make( 'cms::layouts.master', compact('cont'));
            return($layout);
        }
    }
    
    /**
     * Sets language of back end
     **/
    public function anySetlang(){
        
    }
    
    public function anyUpdate(){
        $input = array_except(Input::all(), '_method');        
        $validation = Validator::make($input, Content::$rules);
        if ($validation->passes()){
            $application = Application::getApplication();

            $application->update($input);
            if(@$input['setting']){
                foreach($settingGroup as $type=>$setGrp){
                    foreach($setGrp as $key=>$setting){
                        //we want to delete this setting.
                        if(is_array($setting) && array_key_exists('deleted',$setting)){
                            $applicationSetting = Applicationsetting::destroy($key);
                        }
                        else{
                            //if it's not found (even in trashed) then we need to make a new field.
                            //if it's contentdefault, we need to create it too since it doesn't exist!
                            
                            //otherwise this field exists.. we can overwrite it' settings.
                            $applicationSetting->name = $name;
                            $applicationSetting->value = $setting;
                            $applicationSetting->application_id = $application->id;
                            $applicationSetting->field_type = $applicationSetting->field_type?$applicationSetting->field_type:'text';


                            $applicationSetting->save();
                            $applicationSetting->restore();     //TODO: do we always want to restore the deleted field here?
                        }
                    }
                }
            }
            dd($input);
            dd('save');
        }
    
    }


}