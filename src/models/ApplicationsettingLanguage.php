<?php
class ApplicationsettingLanguage extends Eloquent
{
    protected $table = 'application_settings_lang';
    protected $fillable = array('aplication_setting_id', 'name', 'value', 'field_type');

    public function application()
    {
        return $this->belongsTo('Application', 'application_id');
    }

    public function applicationlanguage()
    {
        return $this->belongsTo('Application', 'application_id');
    }

    public function applicationsetting()
    {
        return $this->belongsTo('Applicationsetting', 'application_setting_id');
    }
    
}