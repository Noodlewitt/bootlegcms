<?php

class TemplatesettingLanguage extends Eloquent {
    
    protected $table = 'template_settings_lang';
    protected $fillable = array('template_setting_id', 'name', 'value', 'field_type');

    public function template()
    {
        return $this->belongsTo('template', 'template_id');
    }

    public function templatelanguage()
    {
        return $this->belongsTo('template', 'template_id');
    }

    public function templatesetting()
    {
        return $this->belongsTo('Templatesetting', 'template_setting_id');
    }
}
