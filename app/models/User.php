<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    protected $fillable = array('id', 'username', 'password', 'email', 'role_id', 'status');

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
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required',
        'password_confirm' => 'required|same:password',
    );
    
    public function role()
    {
        return $this->belongsTo('Role');
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

    /*
    Mutator for password setting.
    */
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }
}
