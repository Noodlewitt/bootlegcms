<?php

class Contentwrap extends Baum\Node{ 
    
    //this needs to be here for Baum's sake.. doesn't like it if we move it to the model.
    //protected $scoped = array('application_id');
    
    //ok we can think about overriding some basic find functions in here:
    //TODO: look at automatically adding settings or maybe adding aplication filter, draft etc.
    //..we'd need to overide Baum's stuff not the default.
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