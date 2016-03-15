<?php namespace Bootleg\Cms; 

use Application;
use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Permission;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    protected $permissions;
    protected $table = 'users';
    protected $fillable = ['id', 'username', 'password', 'email', 'role_id', 'status'];
    protected $hidden = ['password', 'remember_token'];

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'to');
    }

    public function sentNotifications()
    {
        return $this->morphMany(NotificationMessage::class, 'from');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function author(){
        return $this->hasMany(Content::class);
    }

    public function permission()
    {
        return $this->morphMany(Permission::class, 'requestor');
    }

    public function applications(){
        return $this->hasMany(Application::class, 'user_id');
    }

    public function getPermissions()
    {
        if (!isset($this->permissions))
        {
            $this->permissions = Permission::where(function ($query)
            {
                $query->where(function ($query)
                {
                    $query->where(function ($query)
                    {
                        $query->where('requestor_id', '=', $this->id)
                            ->orWhere('requestor_id', '=', '*');
                    })
                        ->where('requestor_type', '=', 'user');
                })
                    ->orWhere(function ($query)
                    {    //where role
                        $query->where(function ($query)
                        {
                            $query->where('requestor_id', '=', $this->role_id)
                                ->orWhere('requestor_id', '=', '*');
                        })
                            ->where('requestor_type', '=', 'role');
                    });
            })
                ->where(function ($query)
                {
                    $app_id = Application::getApplication()->id;
                    $query->where('application_id', $app_id)
                        ->orWhere('application_id', '*');
                })->get();
        }

        return $this->permissions;
    }
    /*
    Mutator for password setting.
    */
   /* public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }*/
}
