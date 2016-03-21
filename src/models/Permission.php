<?php
use Bootleg\Cms\User;
use Illuminate\Database\Eloquent\Model;

class Permission extends Eloquent {

    //This should hold the actual permission table.. who is allowed into what.

    protected $table = 'permissions';
    protected static $user;

    //allows permission->controller
    public function controller()
    {
        return $this->morphTo();
    }

    //allows permission->requestor
    public function requestor()
    {
        return $this->morphTo();
    }

    //a permissions query you can dump into your query.
    public static function hasPermission($controller_type = '', Model $user)
    {

        if ( ! $user instanceof User) $user = User::find($user->id);

        $out = $user->getPermissions()->filter(function ($permission) use ($controller_type) {
            if ($permission->controller_id == '*' || $permission->controller_type = $controller_type) return true;
        });

        return ['permission' => $out];
    }

    //$perms = Auth::user()->permission()->where('controller_type','=','content')->get();
    //$c = Content::permission()->perm()->get();

    public static function checkPermission($controller_type, $controller_id = null, $message = "You do not have permission to do that.")
    {

        $perm = self::getPermission($controller_type, $controller_id);

        if ($perm->result === false) {
            //we can redirect!
            if ($message) {
                return Redirect::guest(config('bootlegcms.cms_route') . 'login')->with('danger', $message);
            } else {
                return Redirect::guest(config('bootlegcms.cms_route') . 'login');
            }

        } else {
            return (true);
        }
    }

    public static function getPermission($controller_type, $controller_id = null, $return = false)
    {
        if ( ! static::$user) static::loadUserPermissions();

        $controller_type = trim($controller_type, '/\\');

        $perm = static::$user->permissions->filter(function ($permission) use ($controller_type, $controller_id) {
            if ($permission->controller_type == $controller_type && ($permission->controller_id == '*' || $permission->controller_id == $controller_id)) return true;
        });

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
            }
        }

        $return->set = $perm;

        return $return;
    }

    public static function getControllerPermission($controller_id, $controllerAction)
    {

        $perm = Permission::where(function ($query) use ($controllerAction, $controller_id) {
            $query->where('controller_type', '=', $controllerAction)->where(function ($query) use ($controller_id) {
                    $query->where('controller_id', '=', $controller_id)->orWhere('controller_id', '=', '*');
                });
        })->orderBy('controller_id', 'desc')->get();

        return ($perm);
    }

    public static function loadUserPermissions()
    {
        if ( ! static::$user) {
            static::$user = Auth::user();
            if (Auth::guest()) static::$user = User::find(1);  //select the guest row.
        }

        if ( ! isset(static::$user->relations['permissions'])) {
            $permissions = static::where(function ($query) {
                $query->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('requestor_id', '=', static::$user->id)->orWhere('requestor_id', '=', '*');
                    })->where('requestor_type', '=', 'user');
                })->orWhere(function ($query) {    //where role
                        $query->where(function ($query) {
                            $query->where('requestor_id', '=', static::$user->role_id)->orWhere('requestor_id', '=', '*');
                        })->where('requestor_type', '=', 'role');
                    });
            })->where(function ($query) {
                    $query->where('application_id', Application::getApplication()->id)->orWhere('application_id', '*');
                })->get();

            static::$user->setRelation('permissions', $permissions);
        }
    }
}
