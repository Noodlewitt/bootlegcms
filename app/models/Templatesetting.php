<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Templatesetting extends Eloquent {
    protected $fillable = array('template_id', 'name', 'value', 'field_type');

    protected $table = 'template_settings';
    
    use SoftDeletingTrait;
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

    
    
    public function content(){
        return($this->belongsTo('Content'));
    }

    public function template(){
        return($this->belongsTo('Template'));
    }
}