<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class Contentsetting extends Eloquent {
    protected $fillable = array('content_id', 'name', 'value', 'field_type');

    protected $table = 'content_settings';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    const DEFAULT_UPLOAD_JSON = '{
        "validation": {
          "mimes":"gif,jpeg,bmp,png",
          "size":"5120"
        },
        "taggable": false,
        "show_preview": true,
        "tooltip": "",
        "max_number": 1,
        "s3_enabled" : 1
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
            "format": "YYYY/MM/DD H:mm:SS",
            "view_mode": "days",
            "pick_time": "true"
        },
        "max_number":1,
        "tooltip": ""
    }';

    const DEFAULT_CHECKBOX_JSON = '{
        "values": {
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
        else if($setting->field_type == 'relationship'){
            $params = self::DEFAULT_RELATIONSHIP_JSON;
        }
        else if($setting->field_type == 'radio'){
            $params = self::DEFAULT_RADIO_JSON;
        }
        else{
            $params = self::DEFAULT_TEXT_JSON;
        }
        return($params);
    }
}
