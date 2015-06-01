<?php namespace Bootleg\Themes;

class ApplicationTheme extends \Eloquent {
    protected $table = 'application_plugin';
    public function applications(){
        return($this->belongsTo('Application', 'application_id'));
    }

    public function themes(){
        return($this->belongsTo('Theme', 'plugin_id'));
    }

    public function settings(){
        return($this->hasMany('Bootleg\Themes\ThemeSetting', 'application_plugin_id'));
    }

    
}
