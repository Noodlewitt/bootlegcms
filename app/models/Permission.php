<?php
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
    
    //$perms = Auth::user()->permission()->where('controller_type','=','content')->get();
    //$c = Content::permission()->perm()->get();
    
    public static function checkPermission($controller_type, $controller_id = NULL, $type, $message='You do not have permission'){

        $perm = self::getPermission($controller_type, $controller_id, $type);   

        if($perm === false){
            //we can redirect!

            return Redirect::guest(Utils::cmsRoute.'login')
                ->with('message', $message);
        }
        
        else return(true);
    }
    
    public static function getPermission($controller_type, $controller_id = NULL, $type, $return = false){
        //do we use complex permissions on this site?
        
        //check permisssion against user
        if(Auth::guest()){
            $user = User::find(0);
        }
        else{
            $user = Auth::user();
        }
        
        $user_permission = $user->permission()->where('controller_type','=',$controller_type)
                                                ->where('controller_id','=',$controller_id)->first();
        
        //and we need the group perms.
        $role_permission = $user->role()->first()->permission()->where('controller_type','=',$controller_type)
                                                            ->where('controller_id','=',$controller_id)->first();

        $result = NULL;
        
        if(@$user_permission){
            if(@$user_permission->$type === '1'){
                $result = true;
            }
            else if(@$user_permission->$type === '0'){
                $result = false;
            }
        }
        
        if(@$role_permission){
            if(@$role_permission->$type === '1' && is_null($result)){
                $result = true;
            }
            else if(@$role_permission->$type === '0' && is_null($result)){
                $result = false;
            }
        }
        
        if($return){
            return(array('role'=>@$role_permission, 'user'=>@$user_permission));
        }
        
        return($result);
    }
    
}