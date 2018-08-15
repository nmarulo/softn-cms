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
        });
    });
    Route::post('/login', 'Api/LoginApi@login', 'login', 'api');
});
