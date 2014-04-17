<?php
class Application extends Eloquent {
    protected $table = 'applications';
    
    protected $_settings = NULL; //holds settings for this application item so we don't have to contantly query it.
    
    public function url(){
        return($this->hasMany('ApplicationUrl'));
    }
    
    public function setting(){
        return($this->hasMany('Applicationsetting'));
    }
    
    public function theme(){
        return($this->belongsTo('Theme'));
    }
    
    public function permission(){
        return $this->morphMany('Permission', 'controller');
    }
    
    public static function getApplication($domain='', $folder = '', $getfromsession = true, $setsession = true){
        //dd($_SERVER['SERVER_NAME']);
        if(!$domain){
            $domain = trim($_SERVER['SERVER_NAME']);
        }

        if(!$folder){
            $folder = str_replace('public/index.php','',$_SERVER['SCRIPT_NAME']);
            $folder = trim(str_replace('public/','',$folder),'/');
            $folder = trim(str_replace('index.php','',$folder),'/');
            if(!$folder){
                $folder = '/';
            }
        }
               
        if($getfromsession){
            if(Session::get('application')){
                $application = Session::get('application');
            }
            else{
                $application = ApplicationUrl::where('domain','=',"$domain")->where('folder','LIKE',"$folder")->first()->application()->first();
            }
        }
        else{
            $application = ApplicationUrl::where('domain','=',"$domain")->where('folder','LIKE',"$folder")->first()->application()->first();
        }
        
        if($setsession && !Session::get('application')){
            Session::put('application', $application);
        }
        
        return($application);
    }
    
    //returns a setting from the name.
    public function getSetting($name){
        
        if(is_null($this->_settings)){
            $this->_settings = $this->setting()->get();
        }
        
        $settings = $this->_settings->filter(function($setting) use ($name){
            if(@$setting->name == $name){
                return($setting);
            }
        });
        if(is_null($settings)){
            return(false);
        }
        else{
            if(count($settings) > 1){
                return(@$settings);
            }
            else{
                return(@$settings->first());
            }        
        }
    }
}