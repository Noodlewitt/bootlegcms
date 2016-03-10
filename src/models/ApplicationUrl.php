<?php

class ApplicationUrl extends Eloquent {

    protected $fillable = ['protocol', 'domain', 'folder'];


    public function application()
    {
        return $this->belongsTo('Application');
    }

    public static function getBaseUrl()
    {
        $applicationurl = unserialize($GLOBALS['applicationurl']);

        if ( $applicationurl->protocol ) {
            $url = $applicationurl->protocol;
        } else {
            $url = "//";
        }
        if ( substr($applicationurl->domain, -1) == '/' || substr($applicationurl->folder, 0, 1) == '/' ) {
            $url .= $applicationurl->domain . $applicationurl->folder;
        } else {
            //we have to add the slash in manually.
            $url .= $applicationurl->domain . '/' . $applicationurl->folder;
        }

        return ($url);
    }

    public static function getApplicationUrl($domain = '', $folder = '', $getFromSession = true, $setSession = true)
    {
        //dd($_SERVER['SERVER_NAME']);
        if ( ! $domain ) {
            $domain = ApplicationUrl::getDomain();
        }

        if ( ! $folder ) {
            $folder = ApplicationUrl::getFolder();
        }

        $prefix = static::getPrefix();


        $applicationUrl = ApplicationUrl::with('application', 'application.setting', 'application.languages', 'application.plugins', 'application.url', 'application.secure_url')
            ->where('domain', '=', "$domain")
            ->where('folder', 'LIKE', $folder)
            ->where(function ($sq) use ($prefix) {
                $sq->where('prefix', 'LIKE', $prefix)->orWhere('prefix', '')->orWhereNull('prefix');
            })
            ->orderBy('ssl', \Request::secure() ? 'DESC' : 'ASC')
            ->orderBy('prefix', 'DESC')
            ->first();

        return ($applicationUrl);
    }

    public static function getFolder()
    {
        $folder = str_replace('public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $folder = trim(str_replace('public/', '', $folder), '/');
        $folder = trim(str_replace('index.php', '', $folder), '/');
        if ( $folder != '/' ) {
            $folder = '/' . $folder;
        }

        return ($folder);
    }

    public static function getPrefix()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);

        if ( isset($url['path']) ) {
            $path = explode('/', $url['path']);

            $folder = static::getFolder();

            if ( $folder == '/' ) {
                if ( isset($path[1]) ) return $path[1];
            } else {
                if ( isset($path[2]) ) return $path[2];
            }
        }

        return '';
    }

    public static function getDomain()
    {
        if ( @$_SERVER['HTTP_HOST'] ) {
            $domain = trim($_SERVER['HTTP_HOST']);
        }

        return (@$domain);
    }

    public function getUrlAttribute($secure = null)
    {
        $protocol = 'http://';

        if ( $secure == null ) {
            $protocol = \Request::secure() ? 'https://' : 'http://';
        } else {
            $protocol = $secure ? 'https://' : 'http://';
        }

        if ( $protocol == 'https://' && (app()->environment('local') || $this->ssl == 0) ) $protocol = 'http://';

        $folder = ($this->folder ? $this->folder : '/');
        if ( $folder == '/' ) $folder = '';

        return $protocol . $this->domain . $folder;
    }

    public function getUrlPrefixedAttribute($secure = null)
    {
        return $this->getUrlAttribute($secure) . '/' . $this->prefix;
    }
}
