<?php namespace Bootleg\Themes;


class ThemesController extends \CMSController{

    public function __construct() {
        parent::__construct();
    }


    public function anyIndex(){
        $themes = \Bootleg\Themes\Theme::where('type',\Config::get('themes::themes.theme_name'))->get();


        $currentTheme = $this->application->whereHas('plugins', function($q){
            return $q->where('type',\Config::get('themes::themes.theme_name'));
        })->first();


        //\Theme::where('type',\Config::get('themes::themes.theme_name'))->first();
        return $this->render('index',array('themes'=>$themes, 'currentTheme'=>$currentTheme),'themes');
    }

    public function getSetTheme($id){
        $plugin = Bootleg\Themes\Plugin::where('id',$id)->where('type',\Config::get('themes::themes.theme_name'))->firstOrFail();
        $currentTheme = $this->application->theme()->where('type',\Config::get('themes::themes.theme_name'))->get();

        //detach the old application_plugin
        foreach($currentTheme as $curThm){
            $this->application->plugins()->detach($curThm->id);
            //TODO: we also need to remove the theme_settings.            
        }

        //attach the plugin to the application
        $this->application->plugins()->attach($id);

        //get the application plugin id that we just attached.
        //$this->application->plugins()->attach($id);

        //save the cms_package
        $this->application->cms_package = $plugin->package;
        $this->applicaiton->save();

        //We need to set the default settings off the currentTheme
        $defaultSettings = $plugin->default_settings()->get();
        foreach($defaultSettings as $defaultSetting){
            $setting = new Bootleg\Themes\ThemeSetting();
            $setting->name = $defaultSetting->name;
            $setting->value = $defaultSetting->value;
            $setting->field_type = $defaultSetting->field_type;
            $setting->field_parameters = $defaultSetting->field_parameters;
            //TODO: set the application_plugin_id
            //todo: lazy vv
            $setting->save();
        }


    }

    public function getEdit($id){
        //Edit theme page.
        
        $theme = Theme::findOrFail($id);
        $application_theme = $theme->application_theme()->first();

        $settings = $application_theme->settings()->get();

        $settings = $settings->groupBy('name');
        return $this->render('edit',array('theme'=>$theme, 'settings'=>$settings),'themes');
    }

    public function postEdit($id){
        $theme = Theme::findOrFail($id);
        $input = \Input::get();        

        if (isset($input['setting'])) {
            foreach ($input['setting'] as $name => $settingGroup) {

                foreach ($settingGroup as $type => $setGrp) {
                    foreach ($setGrp as $key => $setting) {

                        //we want to delete this setting.
                        $toDel = \Utils::recursive_array_search('deleted', $setGrp);
                        if (is_array($setGrp) && @$toDel) {
                            $themeSetting = ThemeSetting::destroy($toDel);
                        }
                        else {

                            $themeSetting = ThemeSetting::withTrashed()
                                ->where('name', '=', $name)
                                ->where('plugin_id', '=', $theme->id)
                                ->where('id', '=', $key)->first();

                            //if it's not found (even in trashed) then we need to make a new field.
                            if(!$themeSetting){
                                $themeSetting = new ThemeSetting();
                            }

                            //otherwise this field exists.. we can overwrite it' settings.
                            $themeSetting->name = $name;
                            $themeSetting->value = $setting;
                            $themeSetting->plugin_id = $theme->id;
                            $themeSetting->field_type = @$themeSetting->field_type?$themeSetting->field_type:'text';


                            //dd($skuSetting);
                            $themeSetting->save();
                            $themeSetting->restore();     //TODO: do we always want to restore the deleted field here?
                        }
                    }
                }
            }
            return \Redirect::action('Bootleg\Themes\ThemesController@getEdit', array('id'=>$id));
        }
    }
}