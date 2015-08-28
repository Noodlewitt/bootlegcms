<?php namespace Bootleg\Cms\Models\Traits;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

trait HasSettingModelTrait
{

    //__NAMESPACE__


    public function getSetting($getSetting, $first = false, $fallback = null, $do_fallback = false){
        $setting_type = $do_fallback && method_exists($this, $fallback) ? $this->{$fallback} : $this->setting;
        $settings = $setting_type->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
        });
        if($settings->count() == 0){
            if($fallback && !$do_fallback) return $this->getSetting($getSetting, false, $fallback, true); //fallback to default settings
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