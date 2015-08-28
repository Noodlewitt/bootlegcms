<?php namespace Bootleg\Cms;

use DateTime;

class Utils
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
    * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
    * keys to arrays rather than overwriting the value in the first array with the duplicate
    * value in the second array, as array_merge does. I.e., with array_merge_recursive,
    * this happens (documented behavior):
    *
    * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
    *     => array('key' => array('org value', 'new value'));
    *
    * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
    * Matching keys' values in the second array overwrite those in the first array, as is the
    * case with array_merge, i.e.:
    *
    * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
    *     => array('key' => array('new value'));
    *
    * Parameters are passed by reference, though only for performance reasons. They're not
    * altered by this function.
    *
    * @param array $array1
    * @param array $array2
    * @return array
    * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
    * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
    */
    public static function  array_merge_recursive_distinct ( array &$array1, array &$array2 )
    {
      $merged = $array1;

      foreach ( $array2 as $key => &$value )
      {
        if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
        {
          $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
        }
        else
        {
          $merged [$key] = $value;
        }
      }

      return $merged;
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
