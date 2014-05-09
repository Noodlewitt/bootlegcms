<?php
class Contentsetting extends Eloquent {
    protected $fillable = array('content_id', 'name', 'value', 'field_type');

    protected $table = 'content_settings';
    
    protected $softDelete = true;
    
    const DEFAULT_UPLOAD_JSON = '{
        "validation": [
          "mimes:gif,jpeg,bmp,png",
          "size:5120"
        ],
        "tooltip": "",
        "count": 1
    }';
    
    const DEFAULT_DROPDOWN_JSON = '{
        "count": 1,
        "values": {
          "myval": "blargh",
          "myval2": "blargh"
        }
        "tooltip": "",
    }';
    
    const DEFAULT_TEXT_JSON = '{
        "count": 1,
        "tooltip":""
    }';
    
    
    public function content(){
        return($this->belongsTo('Content'));
    }
    
    public function default_setting(){
        return $this->belongsTo('Contentdefaultsetting', 'default_id');
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

        if(!($params = json_decode($params))){
            $params = self::getDefaultParams($setting);
            $params = json_decode($params);
        }

        if(@$params->validation){
            $params->parsedValidation = array();
            foreach($params->validation as $rule){
                $exploded = explode(':', $rule);
                $params->parsedValidation[$exploded[0]] = $exploded[1];
            }
        }
        return($params);

    }
    
    public static function getDefaultParams($setting){
        //todo: there must be a nicer way than this..
        if($setting->field_type == 'upload'){
            $params = self::DEFAULT_UPLOAD_JSON;
        }
        else if($setting->field_type == 'dropdown'){
            $params = self::DEFAULT_DROPDOWN_JSON;
        }
        else{
            $params = self::DEFAULT_TEXT_JSON;
        } 
        return($params);
    }
}