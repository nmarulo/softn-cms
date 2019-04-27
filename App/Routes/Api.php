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
            Route::get('/{id?}', 'Api/Dashboard/UsersApi@get', 'users', 'api');
            Route::post('/', 'Api/Dashboard/UsersApi@post', 'users', 'api');
            Route::delete('/{id?}', 'Api/Dashboard/UsersApi@delete', 'users', 'api');
            Route::put('/{id?}', 'Api/Dashboard/UsersApi@put', 'users', 'api');
        });
        Route::group(['prefix' => 'settings'], function(){
            Route::get('/{id?}', 'Api/Dashboard/SettingsApi@get', 'settings', 'api');
            Route::put('/{id?}', 'Api/Dashboard/SettingsApi@put', 'settings', 'api');
        });
        Route::group(['prefix' => 'profiles'], function() {
            Route::get('/{id?}', 'Api/Dashboard/Users/ProfilesApi@get', 'profiles', 'api');
            Route::post('/', 'Api/Dashboard/Users/ProfilesApi@post', 'profiles', 'api');
            Route::delete('/{id?}', 'Api/Dashboard/Users/ProfilesApi@delete', 'profiles', 'api');
            Route::put('/{id?}', 'Api/Dashboard/Users/ProfilesApi@put', 'profiles', 'api');
        });
    });
    Route::post('/login', 'Api/LoginApi@login', 'login', 'api');
    Route::post('/register', 'Api/RegisterApi@register', 'register', 'api');
});
