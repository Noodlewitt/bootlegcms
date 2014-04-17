<?php
class Applicationsetting extends Eloquent {
    protected $table = 'application_settings';
    
    public function application(){
        return($this->belongsTo('Application'));
    }
    
}