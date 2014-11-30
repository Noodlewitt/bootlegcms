<?php

class ApplicationUrl extends Eloquent {
    public function application(){
    	return $this->belongsTo('Application');
    }
    
    public static function getApplicationUrl($domain='', $folder = '', $getFromSession = true, $setSession = true){
        //dd($_SERVER['SERVER_NAME']);
        if(!$domain){
            $domain = ApplicationUrl::getDomain();
        }

        if(!$folder){
            $folder = ApplicationUrl::getFolder();
        }
               
        if($getFromSession){
            if(Session::get('application_url'.$folder)){
                $applicationUrl = Session::get('application_url'.$folder);
            }
            else{
                
                $applicationUrl = ApplicationUrl::with('application','application.setting')->where('domain','=',"$domain")
                          ->where('folder','LIKE',"$folder")->first();
                //$a = ($applicationUrl->application()->first()->id);
                //dd($a);
                //dd(DB::getQueryLog());
                //$application = ApplicationUrl::where('domain','=',"$domain")->where('folder','LIKE',"$folder")->first()->application()->with('setting')->first();
            }
        }
        else{
            $applicationUrl = ApplicationUrl::with('application')->where('domain','=',"$domain")
                          ->where('folder','LIKE',"$folder")->first();
        }
        
        if($setSession && !Session::get('application_url'.$folder)){
            Session::put('application_url'.$folder, $applicationUrl);
        }
        
        return($applicationUrl);
    }
    
    public static function getFolder(){
        $folder = str_replace('public/index.php','',$_SERVER['SCRIPT_NAME']);
        $folder = trim(str_replace('public/','',$folder),'/');
        $folder = trim(str_replace('index.php','',$folder),'/');
        if(!$folder){
            $folder = '/';
        }
        return($folder);
    }
    
    public static function getDomain(){
        $domain = trim($_SERVER['HTTP_HOST']);
        return($domain);
    }   
}
