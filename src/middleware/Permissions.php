<?php namespace Bootleg\Cms\Middleware;

use Auth;
use Closure;
use Permission;
use Route;
use Session;
use URL;

class Permissions {


    /**
     * Test handler
     * @param  [type]  $request [description]
     * @param  Closure $next    [description]
     * @return [type]           [description]
     */
    public function handle($request, Closure $next){
        
        $controller_id = str_replace('/index', '', action("\\". Route::currentRouteAction()));
        $controller_id = (str_replace($controller_id, '', URL::current()));
        $controller_id = trim($controller_id, '/');

        $perm = Permission::checkPermission(Route::currentRouteAction(), $controller_id, false);

        if ($perm !== true) {
            if(@Auth::user()->id){
                Session::flash('danger', "You do not have permission to do that.");
                if(starts_with(URL::current(), URL::to(config('bootlegcms.cms_route'))) && starts_with(URL::previous(), URL::to(config('bootlegcms.cms_route')))) return redirect()->back()->withInput();
            }
            
            return($perm);
        }


        return $next($request);
    }
}
