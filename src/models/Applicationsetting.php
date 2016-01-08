<?php

class Applicationsetting extends Eloquent {

    protected $fillable = ['application_id', 'name', 'value', 'field_type'];
    protected $table = 'application_settings';

    public function application()
    {
        return ($this->belongsTo('Application'));
    }

    public function getNameAttribute()
    {
        return unslug($this->attributes['name'], true);
    }

}
