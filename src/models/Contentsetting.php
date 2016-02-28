<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class Contentsetting extends Eloquent {
    protected $fillable = array('content_id', 'name', 'value', 'field_type');

    protected $table = 'content_settings';
    protected $language = '';
    protected $orig_value = '';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    const DEFAULT_UPLOAD_JSON = '{
        "validation": {
          "mimes":"gif,jpeg,bmp,png",
          "size":"5120"
        },
        "tooltip": {
            "text":"",
            "postion":"top"
        },
        "max_number": 1
    }';
    
    const DEFAULT_DROPDOWN_JSON = '{
        "values": {
          "": "Please Select",
          "customise": "You need to cusomise the values from field_parameters"
        },
        "max_number":1,
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';

    const DEFAULT_DATEPICKER_JSON = '{
        "options":{
            "data-date-format": "DD/MM/YYYY",
            "data-date-today-button": "true"
        },
        "max_number":1,
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';

    const DEFAULT_CHECKBOX_JSON = '{
        "values": {
          "checked": "1",
          "unchecked": "0"
        },
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';
    
    const DEFAULT_TEXT_JSON = '{
        "max_number":1,
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';

    const DEFAULT_TAGS_JSON = '{
        "values": {

        },
        "max_number":1,
        "tooltip": {
            "text":"",
            "postion":"top"
        }
    }';

    const DEFAULT_TINYMCE_JSON = '{
        "tooltip":"",
        "max_number":1,
        "height":300
    }';
    
    
    public function languages(){
        $code = App::getLocale();
        $langs = $this->hasMany('ContentsettingLanguage', 'content_setting_id');
        $langs = $langs->where('code',$code);
        return($langs);
    }

    public function multichildren(){
        return $this->hasMany('Contentsetting', 'parent_id');
    }

    public function templatesetting(){
        return $this->belongsTo('Templatesetting', 'templatesetting_id');
    }
    
    public function content(){
        return $this->belongsTo('Content');
    }

    /*
     * Grabs the params field from wherever it can and parses the json.
     */
    public static function parseParams($setting){
        if(@$setting->field_parameters){
            $params = $setting->field_parameters;
        }
        else if(@$setting->default_setting->field_parameters){
            $params = @$setting->default_setting->field_parameters;
        }
        else{
            $params = self::getDefaultParams($setting);
        }
        return(json_decode($params));
    }
    
    public static function getDefaultParams($setting){
        //todo: there must be a nicer way than this..
        
        if($setting->field_type == 'upload'){
            $params = self::DEFAULT_UPLOAD_JSON;
        }
        else if($setting->field_type == 'dropdown'){
            $params = self::DEFAULT_DROPDOWN_JSON;
        }
        else if($setting->field_type == 'checkbox'){
            $params = self::DEFAULT_CHECKBOX_JSON;
        }
        else if($setting->field_type == 'datepicker'){
            $params = self::DEFAULT_DATEPICKER_JSON;
        }
        else if($setting->field_type == 'tinymce'){
            $params = self::DEFAULT_TINYMCE_JSON;
        }
        else if($setting->field_type == 'tags'){
            $params = self::DEFAULT_TAGS_JSON;
        }
        else{
            $params = self::DEFAULT_TEXT_JSON;
        } 
        return($params);
    }

    public function getValueAttribute($value){
        //if we cant find a value we want a template value.
        return $value;
        if(!isset($this->orig_value)){
            if(config('bootlegcms.cms_languages')){
                $this->language = $this->languages->first();
            }        
        }
        $this->orig_value = $value;
        return @$this->language->value?$this->language->value:$value;
    }

    public function getNameAttribute($name){
        return $name;
        if(!isset($this->orig_name)){
            if(config('bootlegcms.cms_languages')){
                $this->language = $this->languages->first();
            }
        }
        $this->orig_name = $name;
        return @$this->language->name?$this->language->name:$name;
    }

    /**
     * Given a content item, return the settings related OR the template settings related.
     * @param  [type] $content content model
     * @return [type] Collection collection of settings or template settings.
     */
    public static function collectSettings($content){

        $all_settings = new \Illuminate\Database\Eloquent\Collection;

        if (!empty($content->template_setting)) {
            foreach ($content->template_setting as $template_setting) {
                $all_settings->push($template_setting);
            }
        }
        else{

            //we need to grab the template setting..
            $templateSettings = \Templatesetting::where('template_id', $content->template_id)->get();
            foreach ($templateSettings as $template_setting) {
                $all_settings->push($template_setting);
            }
        }

        $all_settings = $all_settings->keyBy('name');

        if (!empty($content->setting)) {
            foreach ($content->setting as $setting) {
                $all_settings->push($setting);
            }
        }
        
        $all_settings = $all_settings->keyBy('name');
        //dd($all_settings['banner social links position']);
        //$settings = $all_settings->groupBy('section');
        return $all_settings;
    }
}
