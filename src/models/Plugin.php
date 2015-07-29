<?php

class Plugin extends Eloquent {

    protected $table = 'plugins';
    public function applications(){
        return($this->belongsToMany('Application'));
    }
    public function setting(){
        return $this->hasMany('Pluginsetting');
    }
    public function defaultsetting(){
        return $this->hasMany('Plugindefaultsetting');
    }
    public function getSetting($getSetting, $default = false){
        $setting_type = $default ? $this->defaultsetting : $this->setting; //are we getting default or normal setting? default: normal

        $settings = $setting_type->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
        });

        if($settings->count() == 0){
            if($default == false) return getSetting($getSetting, true); //fallback to default settings
            else return null; //if no default setting, return null
        }
        if($settings->count() > 1){
            $return = array();
            foreach($settings as $setting){
                $return[] = $setting->value;
            }
        }
        else{
            $return = $settings->first()->value;
        }
        return($return);
    }
}
