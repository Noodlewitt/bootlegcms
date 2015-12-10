<?php namespace Bootleg\Cms; 

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Eloquent implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    protected $fillable = ['id', 'username', 'password', 'email', 'role_id', 'status'];


    protected $permissions;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'users';

    /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    protected $hidden = array('password');

    public static $rules = array(
    //'content' => 'required',
    //'parent_id' => 'required'
       // 'username' => 'required|unique:users',
       // 'email' => 'required|email|unique:users',
        //'password' => 'required',
        //'password_confirm' => 'required|same:password',
    );
    
    public function role()
    {
        return $this->belongsTo('\Role');
    }
    
    
    public function author(){
        return $this->hasMany('Content');
    }

    public function permission()
    {
        return $this->morphMany('Permission', 'requestor');
    }
        
    /**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
	 * Get the password for the user.
	 *
	 * @return string
	 */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
    public function getReminderEmail()
    {
        return $this->email;
    }
        

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function applications(){
        return $this->hasMany('Application', 'user_id');
    }

    public function getPermissions()
    {
        if (!isset($this->permissions))
        {
            $this->permissions = \Permission::where(function ($query)
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
                    $app_id = \Application::getApplication()->id;
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
