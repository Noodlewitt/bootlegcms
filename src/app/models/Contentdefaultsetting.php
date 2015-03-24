<?php

class Contentdefaultsetting extends Eloquent {
    
  
    protected $table = 'content_default_settings';
    
    public function default_page(){
        return $this->hasMany('Contentdefaultpage', 'content_type_id');
    }
    
    public function settings(){
        return $this->hasMany('Contentsetting', 'default_id');
    }
}