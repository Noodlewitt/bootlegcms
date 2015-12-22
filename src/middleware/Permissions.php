<?php namespace Bootleg\Cms\Middleware;

use Closure;

class Permissions {


    /**
     * Test handler
     * @param  [type]  $request [description]
     * @param  Closure $next    [description]
     * @return [type]           [description]
     */
    public function handle($request, Closure $next){
        
        $controller_id = str_replace('/index','',action("\\".\Route::currentRouteAction()));
        $controller_id = (str_replace($controller_id,'',\URL::current()));
        $controller_id = trim($controller_id, '/');

        $perm = \Permission::checkPermission(\Route::currentRouteAction(), $controller_id, false);
        if ($perm === true) {
            //preceed with the normal request
        } 
        else {
            if(@\Auth::user()->id){
                \Session::flash('danger', "You do not have permission to do that.");    
            }
            
            return($perm);
        }


        return $next($request);
    }
}