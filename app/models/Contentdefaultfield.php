<?php

class Contentdefaultfield extends Eloquent {
    
  
    protected $table = 'content_default_fields';

    
    public function content(){
        return $this->hasMany('Content', 'content_type_id');
    }
}