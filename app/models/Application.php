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
    
    public static function getApplication($domain='', $folder = '', $getFromSession = true, $setSession = true){
        //dd($_SERVER['SERVER_NAME']);
        if(!$domain){
            $domain = ApplicationUrl::getDomain();
        }

        if(!$folder){
            $folder = ApplicationUrl::getFolder();
        }
               
        if($getFromSession){
            if(Session::get('application'.$folder)){
                $application = Session::get('application'.$folder);
            }
            else{
                $application = ApplicationUrl::where('domain','=',"$domain")->where('folder','LIKE',"$folder")->first()->application()->with('setting')->first();
            }
        }
        else{
            $application = ApplicationUrl::where('domain','=',"$domain")->where('folder','LIKE',"$folder")->first()->application()->with('setting')->first();
        }
        
        if($setSession && !Session::get('application')){
            Session::put('application'.$folder, $application);
        }
        
        return($application);
    }
    
    /*
     * returns a single setting given the name;
     */
    public function getSetting($getSetting){
        return($this->setting->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
            
        })->first()->value);
    }
}