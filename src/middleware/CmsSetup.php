<?php namespace Bootleg\Cms\Middleware;

use Application;
use Closure;
use Event;
use Illuminate\Contracts\Routing\Middleware;
use Session;

class CmsSetup implements Middleware {

    public function handle($request, Closure $next){
        //try and pick app from session, if visiting cms
        if(($request->is(rtrim(config('bootlegcms.cms_route'),'/')) || $request->is(config('bootlegcms.cms_route').'*')) && !$request->is(config('bootlegcms.cms_route').'login')){
            $cms_app = Session::get('cms_app');
            if($cms_app){
                $application = Application::with('setting','languages','plugins')->find($cms_app);
                if($application){
                    $GLOBALS['application'] = serialize($application);
                    Event::fire('cms.switched_app');
                }
            }
        }

        return $next($request);
    }
}