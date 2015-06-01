<?php namespace Bootleg\Themes;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class ThemeSetting extends \Eloquent {
    protected $table = 'plugin_settings';
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    public function theme(){
        return($this->belongsTo('Bootleg\Themes\Theme', 'plugin_id'));
    }

    public function application_setting(){
        return $this->belongsTo('Bootleg\Themes\ApplicationTheme');
    }

}
