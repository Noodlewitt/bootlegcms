<?php

class ApplicationLanguage extends Eloquent
{
    public function application()
    {
        return $this->belongsTo('Application');
    }
}
