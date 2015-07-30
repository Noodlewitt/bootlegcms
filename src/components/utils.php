<?php namespace Bootleg\Cms;

use DateTime;

class utils
{
    /**
     * strips out http:// or https:// from string
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    public static function stripProtocol($string)
    {
        $string = preg_replace('#^https?://#', '', $string);
        return($string);
    }
    
    /**
     * Decodes params on | and : field seperators - returns array.
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function decodeParameters($params)
    {
        $params = explode('|', $params);
        
        $pr = array();
        foreach ($params as $param) {
            $f = explode(':', $param);
            if (@$f[0]) {
                $pr[$f[0]] = @$f[1];
            }
        }
        if (empty($pr)) {
            return false;
        }
        return($pr);
    }

    
    /**
     * Returns time elapsed since given datetime. Returns XX hours ago..
     * @param  [datetime]  $datetime [description]
     * @param  boolean $full     [description]
     * @return [type]            [description]
     */
    public static function time_elapsed_string($datetime, $full = false)
    {
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

            if (!$full) {
                $string = array_slice($string, 0, 1);
            }
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        } else {
            return 0;
        }
    }

    /**
     * if string starts with
     * @param  [type] $haystack [description]
     * @param  [type] $needle   [description]
     * @return [type]           [description]
     */
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    /**
     * if string ends with
     * @param  [type] $haystack [description]
     * @param  [type] $needle   [description]
     * @return [type]           [description]
     */
    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * A recursive array search for some value
     * @param  [type]  $needle   [description]
     * @param  [type]  $haystack [description]
     * @param  boolean $subloop  [description]
     * @return [type]            [description]
     */
    public static function recursive_array_search($needle, $haystack, $subloop = false)
    {
        if ($subloop === false) {
            $resarr = array();
        }
        foreach ($haystack as $key=>$value) {
            $current_key=$key;
            if (is_string($needle)) {
                $needle = trim(strtolower($needle));
            }
            if (is_string($value)) {
                $value = trim(strtolower($value));
            }
            if ($needle===$value or (is_array($value) && self::recursive_array_search($needle, $value, true) === true)) {
                $resarr[] = $current_key;
                if ($subloop === true) {
                    return true;
                }
            }
        }
        return @$resarr;
    }

    /**
     * converts string into something that can be slugged
     * @param  string $text string to slug to
     * @return string       slugged version of string
     */
    public static function slugify($text)
    {
        // Swap out Non "Letters" with a -
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);

        // Trim out extra -'s
        $text = trim($text, '-');

        // Convert letters that we have left to the closest ASCII representation
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Make text lowercase
        $text = strtolower($text);

        // Strip out anything we haven't been able to convert
        $text = preg_replace('/[^-\w]+/', '', $text);

        return $text;
    }
}
