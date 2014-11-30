<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/upload', function(){
    return Redirect::action('PagesController@getUpload');
});

Route::get('/cms/', array('as' => 'dash', function(){
    return Redirect::action('UsersController@anyDashboard');
}));

Route::group(array('prefix'=>Utils::cmsRoute,'before' => 'permission:update'), function(){

    Route::group(array('before' => 'auth'), function(){
        
        Route::controller('content', 'ContentsController');

        Route::controller('application', 'ApplicationController');

        Route::controller('users', 'UsersController');
    });
});
    


Route::any(Utils::cmsRoute.'login', array('uses'=>'UsersController@anyLogin'));


//TODO: should this be moved elsewhere so pacakge routes can be included here?
//this route handles the whole front end of the site.
Route::group(array('before' => 'permission:front'), function(){
    Route::controller('/', 'PageController');
});