<?php

class Pluginsetting extends \Contentsetting {
    protected $table = 'plugin_settings';
    protected $fillable = array('plugin_id', 'name', 'value', 'field_type', 'application_id', 'field_parameters');

    public function application(){
        return $this->belongsTo('Application');
    }
    public function plugin(){
        return $this->belongsTo('Plugin');
    }

    public function __toString(){
        return @$this->value;
    }

}
