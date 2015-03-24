<?php

class Plugin extends Eloquent {
    protected $table = 'plugins';
    public function applications(){
        return($this->belongsToMany('Application'));
    }
    
}
