<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace App\Routes;

use Silver\Core\Route;

Route::group(['prefix' => 'api'], function() {
    Route::group(['prefix' => 'dashboard'], function() {
        Route::group(['prefix' => 'users'], function() {
            Route::get('/{id?}', 'Api/Dashboard/Users/UsersApi@get', 'users', 'api');
            Route::post('/', 'Api/Dashboard/Users/UsersApi@post', 'users', 'api');
            Route::delete('/{id?}', 'Api/Dashboard/Users/UsersApi@delete', 'users', 'api');
            Route::put('/{id?}', 'Api/Dashboard/Users/UsersApi@put', 'users', 'api');
            Route::put('/password/{id?}', 'Api/Dashboard/Users/UsersApi@putPassword', 'users', 'api');
        });
        Route::group(['prefix' => 'settings'], function(){
            Route::get('/form', 'Api/Dashboard/SettingsApi@getForm', 'settings', 'api');
            Route::get('/{id?}', 'Api/Dashboard/SettingsApi@get', 'settings', 'api');
            Route::put('/form', 'Api/Dashboard/SettingsApi@putForm', 'settings', 'api');
            Route::put('/{id?}', 'Api/Dashboard/SettingsApi@put', 'settings', 'api');
        });
        Route::group(['prefix' => 'profiles'], function() {
            Route::get('/{id?}', 'Api/Dashboard/Users/ProfilesApi@get', 'profiles', 'api');
            Route::post('/', 'Api/Dashboard/Users/ProfilesApi@post', 'profiles', 'api');
            Route::delete('/{id?}', 'Api/Dashboard/Users/ProfilesApi@delete', 'profiles', 'api');
            Route::put('/{id?}', 'Api/Dashboard/Users/ProfilesApi@put', 'profiles', 'api');
        });
        Route::group(['prefix' => 'permissions'], function() {
            Route::get('/{id?}', 'Api/Dashboard/Users/PermissionsApi@get', 'permissions', 'api');
            Route::post('/', 'Api/Dashboard/Users/PermissionsApi@post', 'permissions', 'api');
            Route::delete('/{id?}', 'Api/Dashboard/Users/PermissionsApi@delete', 'permissions', 'api');
            Route::put('/{id?}', 'Api/Dashboard/Users/PermissionsApi@put', 'permissions', 'api');
        });
    });
    Route::post('/login', 'Api/LoginApi@login', 'login', 'api');
    Route::post('/register', 'Api/RegisterApi@register', 'register', 'api');
});
