<?php

class Theme extends Eloquent {
    protected $table = 'themes';
    public function application(){
    	return $this->hasMany('Theme');
    }
    
}
