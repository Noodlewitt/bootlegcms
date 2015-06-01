<?php namespace Bootleg\Themes;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class ThemeDefaultSetting extends \Eloquent {
    protected $table = 'plugin_default_settings';
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    public function theme(){
        return($this->belongsTo('Bootleg\Themes\Theme', 'plugin_id'));
    }

    public function settings(){
        return($this->hasMany('Bootleg\Themes\ThemeSetting', 'plugin_id'));
    }
    
}
