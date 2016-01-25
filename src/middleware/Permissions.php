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

        $controller_id = str_replace('/index','',action("\\". Route::currentRouteAction()));
        $controller_id = (str_replace($controller_id,'', URL::current()));
        $controller_id = trim($controller_id, '/');

        $perm = Permission::checkPermission(Route::currentRouteAction(), $controller_id, false);

        if ($perm !== true) {
            //Session::flash('cms_intended', URL::current());
            Session::flash('danger', "You do not have permission to do that.");

            return($perm);
        }

        return $next($request);
    }
}
