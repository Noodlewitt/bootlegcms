<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class Contentsetting extends Eloquent {
    use SoftDeletes;

    protected $fillable = array('content_id', 'name', 'value', 'field_type');
    protected $table = 'content_settings';
    protected $dates = ['deleted_at'];

    const DEFAULT_UPLOAD_JSON = '{
        "validation": {
          "mimes":"gif,jpeg,bmp,png",
          "size":"5120"
        },
        "show_preview": true,
        "tooltip": "",
        "max_number": 1,
        "s3_enabled" : 1,
        "can_tag" : 1
    }';

    const DEFAULT_DROPDOWN_JSON = '{
        "values": {
        },
        "max_number":1,
        "tooltip": ""
    }';

    const DEFAULT_RELATIONSHIP_JSON = '{
        "table":"",
        "id_col": "id",
        "value_col": "name",
        "is_baum":0
    }';

    const DEFAULT_DATEPICKER_JSON = '{
        "options":{
            "data-date-format": "dd/mm/yyyy",
            "data-date-today-button": "true"
        },
        "max_number":1,
        "tooltip": ""
    }';

    const DEFAULT_CHECKBOX_JSON = '{
        "values": {
          "checked": "1",
          "unchecked": "0"
        },
        "tooltip": ""
    }';
    const DEFAULT_RADIO_JSON = '{
        "values": {
        },
        "tooltip": ""
    }';

    const DEFAULT_TEXT_JSON = '{
        "tooltip":"",
        "max_number":1
    }';

    const DEFAULT_TINYMCE_JSON = '{
        "tooltip":"",
        "max_number":1,
        "height":300
    }';


    public function content(){
        return($this->belongsTo('Content'));
    }



    /*
     * Grabs the params field from wherever it can and parses the json.
     */
    public static function parseParams($setting){
        if(@$setting->field_parameters){
            //OLD CODE: Uses supplied parameters, but must include ALL paramaters
            //$params = $setting->field_parameters;

            //NEW CODE: Merges supplied paramaters, and defaults on parameters that were not supplied
            $default_settings = json_decode(self::getDefaultParams($setting), true);
            $provided_settings = json_decode($setting->field_parameters, true);
            $params = json_encode(\Bootleg\Cms\Utils::array_merge_recursive_distinct($default_settings,$provided_settings));
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
        //dd($setting->field_type);

        $field_type = 'DEFAULT_'.strtoupper ($setting->field_type).'_JSON';
        $params = @constant(self.'::'.$field_type);

        //default
        if(!$params){
            $params = self::DEFAULT_TEXT_JSON;
        }

        return($params);
    }
}
