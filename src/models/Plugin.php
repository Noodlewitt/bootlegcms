<?php

class Plugin extends Eloquent {
    use \Bootleg\Cms\Models\Traits\HasSettingModelTrait;

    protected $table = 'plugins';
    public function applications(){
        return($this->belongsToMany('Application'));
    }
    public function setting(){
        return $this->hasMany('Pluginsetting');
    }
    public function default_setting(){
        return $this->hasMany('Plugindefaultsetting');
    }
}
