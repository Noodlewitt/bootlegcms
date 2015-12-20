<?php
use Illuminate\Database\Eloquent\SoftDeletes;
class Applicationsetting extends Eloquent
{

        use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = array('application_id', 'name', 'value', 'field_type');
    protected $table = 'application_settings';
    
    public function application()
    {
        return($this->belongsTo('Application'));
    }

    public function languages($code = NULL){

        $langs = $this->hasMany('ApplicationsettingLanguage', 'application_setting_id');
        if($code){
            $langs->where('code',$code);
        }
        return($langs);
    }

    public function getValueAttribute($value){
        //if we cant find a value we want a template value.
        return $value;
        if(!isset($this->orig_value)){
            if(config('bootlegcms.cms_languages')){
                $this->language = $this->languages(\App::getLocale())->first();
            }        
        }
        $this->orig_value = $value;
        return @$this->language->value?$this->language->value:$value;
    }

    public function getNameAttribute($name){
        return $name;
        if(!isset($this->orig_name)){
            if(config('bootlegcms.cms_languages')){
                $this->language = $this->languages(\App::getLocale())->first();
            }
        }
        $this->orig_name = $name;
        return @$this->language->name?$this->language->name:$name;
    }

    
}