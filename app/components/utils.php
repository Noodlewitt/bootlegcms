<?php
class Utils{
    //WE CAN USE THIS FOR ANY STANDARD STING UTILS
    //Test STring to check deployment scheme
    
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

    
    /*Time Ago*/
    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        if (isset($diff)) {
             $string = array(
             'y' => 'year',
             'm' => 'month',
             'd' => 'day',
             'h' => 'hour',
             'i' => 'minute'
         );

         foreach ($string as $k => &$v) {
             if ($diff->$k) {
                 $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
             } else {
                 unset($string[$k]);
             }
         }

         if (!$full)
             $string = array_slice($string, 0, 1);
         return $string ? implode(', ', $string) . ' ago' : 'just now';
     }
        else {
               return 0;
             }
    }

    public static function startsWith($haystack, $needle){
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    public static function endsWith($haystack, $needle){
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    public static function recursive_array_search($needle,$haystack,$subloop = false) {
        if($subloop === false) {
            $resarr = array();
        }
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if(is_string($needle)) $needle = trim(strtolower($needle));
            if(is_string($value)) $value = trim(strtolower($value));
            if($needle===$value OR (is_array($value) && self::recursive_array_search($needle,$value,true) === true)) {
                $resarr[] = $current_key;
                if($subloop === true) return true;
            }
        }
        return $resarr;
    }
}
