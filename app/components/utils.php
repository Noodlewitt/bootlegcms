<?php
class Utils{
    //WE CAN USE THIS FOR ANY STANDARD STING UTILS
    
    //the route for the cms.
    const cmsRoute = 'cms/';
    /*
     * strips out http:// or https:// from string
     */
    public static function stripProtocol($string){
        $string = preg_replace('#^https?://#', '', $string);
        return($string);
    }
    
    /*
     * Decodes params on | and : field seperators - returns array.
     */
    
    public static function decodeParameters($params){
        $params = explode('|', $params);
        
        $pr = array();
        foreach($params as $param){
            $f = explode(':', $param);
            if(@$f[0]){
                $pr[$f[0]] = @$f[1];
            }
        }
        if(empty($pr)){
            return false;
        }
        return($pr);
    }
}