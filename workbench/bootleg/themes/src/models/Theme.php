<?php namespace Bootleg\Themes;

class Theme extends \Eloquent {
    protected $table = 'plugins';
    public function applications(){
        return($this->belongsToMany('Application'));
    }

    public function application_theme(){
        return($this->hasMany('Bootleg\Themes\ApplicationTheme', 'plugin_id'));
    }

    public function default_settings(){
        return($this->hasMany('Bootleg\Themes\ThemeDefaultSetting', 'plugin_id'));
    }
    
}
