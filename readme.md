
####Setup Instructions:

1. install & setup laravel laravel:
    
    ```
    composer create-project laravel/laravel=5.0.22 mixtapes --prefer-dist
    ```
    ..then do database, migrations, etc

2. add in cms to composer:

    ```    
    composer require 'bootleg/cms:dev-master'
    ```

3. Remove contents of default laravel routes file:
    ```
    app/Http/routes.php
    ```

4. Add in service providers:
    config/app.php:
    ```
    //service providers
    'Bootleg\Cms\CmsServiceProvider', 
    'Collective\Html\HtmlServiceProvider'
    ```
    ```
    //aliasses
    'Form' => 'Collective\Html\FormFacade', 
    'Html' => 'Collective\Html\HtmlFacade',
    ```

5. Register permissions middleware:
    app/Http/Kernal.php:
    ```
    'permissions' => 'Bootleg\Cms\Middleware\Permissions',
    ```

6. Publish assets for cms:
    ```
    php artisan vendor:publish
    ```

7. composer dump-autoload to make sure you've got all the migrations and config files included.
    ```
    composer dump-autoload
    ```
===

You then should set up the database:

8. Run migration:
    ```
    php artisan migrate
    ```

9. Run seeding: 
    ```
    php artisan db:seed --class="Bootleg\Cms\BootlegSeeder"
    ```

===

####You then need to make a theme or plugin.

######via Laravel 4.2's workbench:
You can still use laravel 4's workbench to set up a theme.
1. composer.json:
    ```
    composer require "illuminate/workbench:dev-master"
    ```
    
2. Add in service provider:
    ```
    "Illuminate\Workbench\WorkbenchServiceProvider", 
    ```

3. Create workbench config file
    config/workbench.php
    ```
    <?php
    return [
        'name' => 'Simon Davies',
        'email' => 'whatever@whatever.com',
    ];
    ```
    ..and composer dump-autoload

4. Add run workbench command to create a workbench item:
    ```
    php artisan workbench vendor/package --resources
    ```

5. Add this into composer.json
    ```
    "autoload": {
        "classmap": [
            "workbench/vendor/package/src"
            ..
        ]
    }
    ```
    
6. Optionally add this into the plugins table 
OR 
You can include it into config/app.php

######via Manual:
You can of course create a plugin manually with whatever structure makes sense to you.. A base template for a theme can be found here: [Sample theme](https://github.com/Noodlewitt/bootlegcms-sampleplugin).


