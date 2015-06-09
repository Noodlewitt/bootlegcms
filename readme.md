Notes:

1. install & setup laravel laravel:
    
    composer create-project laravel/laravel --prefer-dist

    ..database, migrations, etc


2. add in cms to composer:
    
    composer require 'bootleg/cms:dev-master'

3. Remove contents of default laravel routes file:
    
    app/Http/routes.php

4. Add in service providers:
    config/app.php:

    service providers:
    'Illuminate\Workbench\WorkbenchServiceProvider',
    'Bootleg\Cms\CmsServiceProvider',
    'Collective\Html\HtmlServiceProvider',


    aliasses
    'Form' => 'Collective\Html\FormFacade',
    'Html' => 'Collective\Html\HtmlFacade',

5. Add in middleware:
    app/Http/Kernal.php:
    'permissions' => 'Bootleg\Cms\Middleware\Permissions',

6. Publish assets for cms:
    php artisan vendor:publish

7. composer dump-autoload
    composer dump-autoload

===

8. Run migration:
    php artisan migrate

9. Run seeding: 




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
