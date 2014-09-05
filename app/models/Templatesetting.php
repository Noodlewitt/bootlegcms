<?php
class Templatesetting extends Eloquent {
    protected $fillable = array('template_id', 'name', 'value', 'field_type');

    protected $table = 'template_settings';
    
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
}