<?php

class ApplicationLanguage extends Eloquent{
    protected $table = 'application_languages';
    public function application()
    {
        return $this->belongsTo('Application');
    }
}
