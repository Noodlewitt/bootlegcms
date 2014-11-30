<?php

class Contentdefaultpage extends Eloquent {
    
  
    protected $table = 'content_default_pages';
    
    public function content(){
        return $this->hasMany('Content');
    }
    
    public function default_settings(){
        return $this->hasMany('Contentdefaultsetting', 'content_type_id');
    }
    
}