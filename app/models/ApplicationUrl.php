<?php

class ApplicationUrl extends Eloquent {
    public function application(){
    	return $this->belongsTo('Application');
    }
}
