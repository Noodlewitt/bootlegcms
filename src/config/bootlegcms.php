<?php

return [
    'cms_debug' => env('CMS_DEBUG', false),                         //the view path of the views that the cms uses
    'cms_hint_path' => env('CMS_HINT_PATH', 'cms'),                 //the view path of the views that the cms uses
    'cms_route' => env('CMS_ROUTE', 'cms/'),                        //the route to get to the cms
    'cms_tree_descendents' => env('CMS_TREE_DESCENDENTS', NULL),    //the number of tree decendents to get at one time - set to NULL to load everything
    'cms_languages' => env('CMS_LANGUAGES', false),                 //enable language handling.
    'cms_pagination' => env('CMS_PAGINATION', 15),                  //number of items per page           
    'cms_content_menu' => env('CMS_CONTENT_MENU', true),            //show the content item on the main menu
    'cms_users_menu' => env('CMS_USERS_MENU', true),                //show the users item on the main menu
    'cms_application_menu' => env('CMS_APPLICATION_MENU', true)     //show applications in menu
];