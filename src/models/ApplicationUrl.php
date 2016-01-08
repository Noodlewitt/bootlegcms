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

        if ($applicationurl->protocol)
        {
            $url = $applicationurl->protocol;
        } else
        {
            $url = "//";
        }
        if (substr($applicationurl->domain, -1) == '/' || substr($applicationurl->folder, 0, 1) == '/')
        {
            $url .= $applicationurl->domain . $applicationurl->folder;
        } else
        {
            //we have to add the slash in manually.
            $url .= $applicationurl->domain . '/' . $applicationurl->folder;
        }

        return ($url);
    }

    public static function getApplicationUrl($domain = '', $folder = '', $getFromSession = true, $setSession = true)
    {
        //dd($_SERVER['SERVER_NAME']);
        if ( ! $domain)
        {
            $domain = ApplicationUrl::getDomain();
        }

        if ( ! $folder)
        {
            $folder = ApplicationUrl::getFolder();
        }


        $applicationUrl = ApplicationUrl::with('application', 'application.setting', 'application.languages', 'application.plugins')->where('domain', '=', "$domain")
            ->where('folder', 'LIKE', "$folder")->first();


        if ($setSession && ! Session::get('application_url' . $folder))
        {
            Session::put('application_url' . $folder, $applicationUrl);
        }

        return ($applicationUrl);
    }

    public static function getFolder()
    {
        $folder = str_replace('public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $folder = trim(str_replace('public/', '', $folder), '/');
        $folder = trim(str_replace('index.php', '', $folder), '/');
        if ($folder != '/')
        {
            $folder = '/' . $folder;
        }

        return ($folder);
    }

    public static function getDomain()
    {
        if (@$_SERVER['HTTP_HOST'])
        {
            $domain = trim($_SERVER['HTTP_HOST']);
        }

        return (@$domain);
    }
}
