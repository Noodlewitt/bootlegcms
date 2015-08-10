<?php

class TemplateLanguage extends Eloquent {
    
    protected $table = 'template_lang';

    protected $fillable = array('name', 'slug', 'user_id', 'template_id', 'code');

    public function template()
    {
        return $this->belongsTo('Template', 'template_id');
    }

    public function templatesettinglanguage(){
        return $this->hasMany('TemplatesettingLanguage', 'template_language_id');
    }
}
