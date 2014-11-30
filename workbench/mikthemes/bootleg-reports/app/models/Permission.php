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

        $perm = true;//self::getPermission($controller_type, $controller_id, $type);   
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



        var_dump('User:'.$user->id);
        var_dump($type);
        echo('<br>');
        $perm = Permission::where('controller_type','=',$controller_type)
                ->where(function($query) use($controller_id){
                    $query->where('controller_id','=',$controller_id)
                        ->orWhere('controller_id','=','*');
                    })
                ->where(function($query) use($user){
                    $query->where(function($query) use($user){
                        $query->where(function($query) use($user){
                            $query->where('requestor_id','=',$user->id)
                                ->orWhere('requestor_id','=','*');
                        })
                        ->where('requestor_type','=','user');
                    });
                })
                ->orderBy('requestor_id', 'desc')
                ->orderBy('controller_id', 'desc')
                ->get();
        
                
        //NULL means we inherit from the next row..
        foreach($perm as $p){
            if($p->$type === '1'){
                var_dump('granted based off:'.$p->id);
                $granted = true;
                break;
            }
            else if($p->type == "0"){
                echo('HEHEHRHHHH');
                var_dump($p->type);
                $granted = false;
                break;
            }
            else{
                var_dump('based off:'.$p->id.':'.$p->$type);
                var_dump($p->$type);
            }
          /*  else if($p->type === '0'){
                $granted = false;
                break;
            }
            else{

                //we inherit it from above.
            }*/
        }




        /*

        TODO: QUERY SHOULD NOW BE GETTING USER PERMISSIONS ON THIS OBJECT.
        we now need to implement the not part. ALSO GROUPS.

        */




   /*     $perm = $user->permission()->where('controller_type','=',$controller_type)
                                   ->where('controller_id','=',$controller_id)->get();
                                   
     */   

        //perm is now all permissions expressly granted to this.. we also need to grab permissions with NOT.

/*        $not = $content->setting->filter(function($d) use($cd){
            return($cd->name===$d->name);
        });  

        $perm->filter(function($data){

        });
        foreach($perm as $p){
            var_dump($p->id);    
        }
*/
        exit();

        //and we need the group perms.
        $role_permission = $user->role()->first()->permission()->where('controller_type','=',$controller_type)
                                                            ->where('controller_id','=',$controller_id)->first();

        $result = NULL;
        //dd(@$user_permission->id);
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