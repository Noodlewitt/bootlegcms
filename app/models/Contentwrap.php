<?php

class Contentwrap extends Baum\Node{ 
    

    
    //ok we can think about overriding some basic find functions in here:
    //TODO: look at automatically adding settings or maybe adding aplication filter, draft etc.
    public function newFromBuilder($attributes = array()){
            //this is the standard stuff it used to do..
            $instance = $this->newInstance(array(), true);
            $instance->setRawAttributes((array) $attributes, true);
            
            
            //we also need to register the service provider of this content 
            //item. This is where the magic happens.
            App::register($instance->service_provider);
            return $instance;
    }
    
}