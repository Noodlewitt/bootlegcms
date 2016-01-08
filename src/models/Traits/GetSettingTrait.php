<?php namespace Bootleg\Cms;

trait GetSettingTrait
{
    public function getSetting($getSetting){
        $settings = $this->setting->filter(function($model) use(&$getSetting){
            return str_slug($model->name, "_") === $getSetting;

        });
        if($settings->count() == 0){
            return null;
        }
        if($settings->count() > 1){
            $return = array();
            foreach($settings as $setting){
                $return[] = $setting->value;
            }
        }
        else{
            $return = $settings->first()->value;
        }
        return($return);
    }
}
