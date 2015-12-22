<?php

class Plugindefaultsetting extends \Eloquent {
    protected $table = 'plugin_default_settings';
    protected $fillable = array('plugin_id', 'name', 'value', 'field_type', 'field_parameters');

    public function plugin(){
        return $this->belongsTo('Plugin');
    }
}
