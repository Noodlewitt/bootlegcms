<?php

/* 
 * AWS stuff.
 */
$application = Application::getApplication(null, null, false, true);
return array(
    'includes' => array('_aws'),
    'services' => array(
        'default_settings' => array(
            'params' => array(
                'key'    => @$application->setting->filter(function($model){return $model->name === 's3 access key';})->first()->value,
                'secret' => @$application->setting->filter(function($model){return $model->name === 's3 secret';})->first()->value,
                'region' => @$application->setting->filter(function($model){return $model->name === 's3 region';})->first()->value
            )
        )
    )
);