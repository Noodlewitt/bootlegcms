<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class Templatesetting extends Eloquent
{
    protected $fillable = array('template_id', 'name', 'value', 'field_type');

    protected $table = 'template_settings';
    
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    const DEFAULT_UPLOAD_JSON = '{
        "validation": {
          "mimes":"gif,jpeg,bmp,png",
          "size":"5120"
        },
        "tooltip": "",
        "count": 1
    }';
    
    const DEFAULT_DROPDOWN_JSON = '{
        "count": 1,
        "values": {
          "myval": "Some Value",
          "myval2": "Some Other Value"
        }
        "tooltip": "",
    }';
    
    const DEFAULT_TEXT_JSON = '{
        "tooltip":"",
        "max_number":"1"
    }';

    const DEFAULT_TAGS_JSON = '{
        "values": {
          "default": "A Default Tag",
          "customise": "If you want more default tags"
        },
        "max_number":1,
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';
    
    public function content()
    {
        return($this->belongsTo('Content'));
    }

    public function template()
    {
        return($this->belongsTo('Template'));
    }
    
    public function multichildren(){
        return $this->hasMany('Templatesetting', 'parent_id');
    }

    public function languages($code = NULL){

        $langs = $this->hasMany('TemplatesettingLanguage', 'template_setting_id');
        if($code){
            $langs->where('code',$code);
        }
        return($langs);
    }


    public function getValueAttribute($value){
        if(config('bootlegcms.cms_languages')){
            $this->language = $this->languages(\App::getLocale())->first();
        }
        $this->orig_value = $value;
        return @$this->language->value?$this->language->value:$value;
    }

    public function getNameAttribute($name){
        if(config('bootlegcms.cms_languages')){
            $this->language = $this->languages(\App::getLocale())->first();
        }
        $this->orig_name = $name;
        return @$this->language->name?$this->language->name:$name;
    }
}
