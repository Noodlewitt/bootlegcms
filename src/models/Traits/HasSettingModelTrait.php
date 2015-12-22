<?php namespace Bootleg\Cms\Models\Traits;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

trait HasSettingModelTrait
{

    //__NAMESPACE__

    /*
        $options = [
            'first'=>false,
            'fallback'=>null,
            'do_fallback'=>true,
            'return_object'=>false
        ];
    */
    public function getSetting($getSetting, $options = []){

        $setting_type = @$options['do_fallback'] && @$options['fallback'] && method_exists($this, $options['fallback']) ? $this->{$options['fallback']} : $this->setting;
        $settings = $setting_type->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
        });
        if($settings->count() == 0){
            if(@$options['fallback'] && !@$options['do_fallback']){
                $options['do_fallback'] = true;
                return $this->getSetting($getSetting, $options); //fallback to default settings
            }
            else
            {
                return null; //if no default setting, return null
            }
        }
        if($settings->count() > 1 && !@$options['first']){
            $return = [];
            foreach($settings as $setting){
                $return[] = $setting;
            }
        }
        else{
            $return = $settings->first();
        }
        if(isset($options['return_object']) && $options['return_object']){
            return $return;
        } elseif (isset($return) && isset($return->value)) {
            return $return->value;
        } else {
            return null;
        }
    }
}