<?php
use Bootleg\Cms\User;
use Illuminate\Database\Eloquent\Model;

class Permission extends Eloquent {
    //This should hold the actual permission table.. who is allowed into what.

    protected $table = 'permissions';

    //allows permission->controller
    public function controller(){
        return $this->morphTo();
    }

    //allows permission->requestor
    public function requestor(){
        return $this->morphTo();
    }



    //a permissions query you can dump into your query.
    public static function hasPermission($controller_type = '', Model $user){

        if (!$user instanceof User) $user = User::find($user->id);

        $out = $user->getPermissions()->filter(function($permission) use ($controller_type) {
            if ($permission->controller_id == '*' || $permission->controller_type = $controller_type) return true;
        });

        return ['permission' => $out];
    }

    //$perms = Auth::user()->permission()->where('controller_type','=','content')->get();
    //$c = Content::permission()->perm()->get();

    public static function checkPermission($controller_type, $controller_id = null, $message="You do not have permission to do that."){

        $perm = self::getPermission($controller_type, $controller_id);

        if ($perm->result === false) {
            //we can redirect!
            if($message){
                return Redirect::guest(config('bootlegcms.cms_route').'login')
                    ->with('danger', $message);
            }
            else{
                return Redirect::guest(config('bootlegcms.cms_route').'login');
            }

        } else {
            return(true);
        }
    }

    public static function getPermission($controller_type, $controller_id = null, $return = false){
        //check permisssion against user
        if (Auth::guest()) {
            $user = User::find(1);  //select the guest row.
        } else {
            $user = User::find(Auth::user()->id);
        }
        $controller_type = trim($controller_type, '/\\');

        //$controller_type = (addslashes($controller_type));

        //$p = Permission::where('controller_type', $controller_type)->first();

        //dd($p->id);
        $perm = $user->getPermissions()->filter(function($permission) use ($controller_type, $controller_id) {
            if ($permission->controller_type == $controller_type && ($permission->controller_id == '*' || $permission->controller_id == $controller_id)) return true;
        });

        //dd($perm);

        $return = new stdClass();
        $return->result = false;
        foreach ($perm as $p) {
            if ($p->x == 1) {
                $return->result = true;
                $return->picked = $p;
                break;
            } elseif ($p->x == 0) {
                $return->result = false;
                $return->picked = $p;
                break;
            } else {
                //var_dump($p->id);
                //we are inheriting from the enxt level up.
            }
        }
        //dd($controller_type, $controller_id, $user->getPermissions(), $perm);
        $return->set = $perm;
        return $return;
    }

    public static function getControllerPermission($controller_id, $controllerAction){

        $perm = Permission::where(function ($query) use ($controllerAction, $controller_id) {
            $query->where('controller_type', '=', $controllerAction)
                  ->where(function ($query) use ($controller_id) {
                        $query->where('controller_id', '=', $controller_id)
                              ->orWhere('controller_id', '=', '*');
                  });
        })
        ->orderBy('controller_id', 'desc')
        ->get();
        return($perm);
    }
}
