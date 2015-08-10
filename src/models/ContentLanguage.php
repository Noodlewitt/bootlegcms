<?php

class ContentLanguage extends Eloquent {
    
    protected $table = 'content_lang';

    protected $fillable = array('name', 'slug', 'user_id', 'content_id', 'code');

    public function content()
    {
        return $this->belongsTo('Content', 'content_id');
    }

    public function contentsettinglanguage(){
        return $this->hasMany('ContentsettingLanguage', 'content_language_id');
    }
}
