<?php namespace Bootleg\Cms\Models\Traits;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

trait HasSettingModelTrait
{

    //__NAMESPACE__


    public function getSetting($getSetting, $default = false, $first = false){
        $setting_type = $default ? $this->default_setting : $this->setting; //are we getting default or normal setting? default: normal

        $settings = $setting_type->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
        });

        if($settings->count() == 0){
            if($default == false) return $this->getSetting($getSetting, true); //fallback to default settings
            else return null; //if no default setting, return null
        }
        if($settings->count() > 1 && !$first){
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