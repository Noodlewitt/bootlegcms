<?php
class Application extends Eloquent {
    protected $table = 'applications';
    protected $fillable = array('name', 'theme_id', 'parent_id', 'cms_theme_id', 'cms_package', 'cms_service_provider', 'package', 'service_provider');
    protected $_settings = NULL; //holds settings for this application item so we don't have to contantly query it.
    
    public static $rules = array(
		//'content' => 'required',
		//'parent_id' => 'required'
    );
    
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