<?php namespace Bootleg\Cms\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;

class CmsSetup implements Middleware {
    /**
     * Test handler
     * @param  [type]  $request [description]
     * @param  Closure $next    [description]
     * @return [type]           [description]
     */

    public function handle($request, Closure $next){
        //try and pick app from session, if visiting cms
        if(($request->is(rtrim(config('bootlegcms.cms_route'),'/')) || $request->is(config('bootlegcms.cms_route').'*')) && !$request->is(config('bootlegcms.cms_route').'login')){
            $cms_app = \Session::get('cms_app');
            if($cms_app){
                $application = \Application::with('setting','languages','plugins')->find($cms_app);
            }
        }
        if(@$application) $GLOBALS['application'] = serialize($application);

        return $next($request);
    }
}