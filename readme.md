Notes:

composer.json:
    add in require:
        "illuminate/workbench": "dev-master",
        "baum/baum": "~1.1",
        "laravelcollective/html": "~5.0"

    add in autoload classmap:
        "workbench/bootleg/cms/src"


Add in middleware:
    app/Http/Kernal.php
    'permissions' => 'Bootleg\Cms\Middleware\Permissions',

Add in service provider:
    config/app.php

    TODO: see what can be included at runtime from SP boot method.
        'Illuminate\Workbench\WorkbenchServiceProvider',
        'Bootleg\Cms\CmsServiceProvider',
        'Collective\Html\HtmlServiceProvider',

    Add in aliasses for html helpers:
        'Form' => 'Collective\Html\FormFacade',
        'Html' => 'Collective\Html\HtmlFacade',


Publish assets for cms:
    php artisan vendor:publish

Publish assets for a theme or plugin:
    php artisan vendor:publish --provider="Bootleg\Cms\CmsServiceProvider"

    ..or add it into composer update script.
    "php artisan asset:publish --provider=\"Bootleg\Cms\CmsServiceProvider\""
