<?php

class Role extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    public function users()
    {
        return ($this->hasMany('Users'));
    }

    public function permission()
    {
        return $this->morphMany('Permission', 'requestor');
    }
}
