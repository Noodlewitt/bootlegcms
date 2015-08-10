<?php

class ContentsettingLanguage extends Eloquent {
    
    protected $table = 'content_settings_lang';
    protected $fillable = array('content_setting_id', 'name', 'value', 'field_type');

    public function content()
    {
        return $this->belongsTo('Content', 'content_id');
    }

    public function contentlanguage()
    {
        return $this->belongsTo('Content', 'content_id');
    }

    public function contentsetting()
    {
        return $this->belongsTo('Contentsetting', 'content_setting_id');
    }
}
