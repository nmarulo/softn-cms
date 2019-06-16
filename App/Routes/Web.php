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

Route::group(['prefix' => 'dashboard'], function() {
    // Route for Dashboard controller.
    Route::get('/', 'Dashboard/Dashboard@index', 'dashboard', 'dashboard');
    // Route for Users controller.
    Route::group(['prefix' => 'users'], function() {
        Route::get('/', 'Dashboard/Users@index', 'users', 'dashboard');
        Route::get('/form/{id?}', 'Dashboard/Users@form', 'users', 'dashboard');
        Route::post('/form/{id?}', 'Dashboard/Users@form', 'users', 'dashboard');
        Route::post('/form/password/{id?}', 'Dashboard/Users@formPassword', 'users', 'dashboard');
        Route::post('/delete/{id?}', 'Dashboard/Users@delete', 'users', 'dashboard');
        Route::group(['prefix' => 'permissions'], function() {
            Route::get('/', 'Dashboard/Users/Permissions@index', 'permissions', 'dashboard');
            Route::post('/form/{id?}', 'Dashboard/Users/Permissions@form', 'permissions', 'dashboard');
            Route::post('/delete/{id?}', 'Dashboard/Users/Permissions@delete', 'permissions', 'dashboard');
            Route::post('/{id?}', 'Dashboard/Users/Permissions@index', 'permissions', 'dashboard');
        });
    });
    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', 'Dashboard/Settings@index', 'settings', 'dashboard');
        Route::post('/', 'Dashboard/Settings@form', 'settings', 'dashboard');
    });
});

// Route for Login controller.
Route::group(['prefix' => 'login'], function() {
    Route::get('/', 'Login@index', 'login', 'login');
    Route::post('/', 'Login@form', 'login', 'login');
});

// Route for Register controller.
Route::group(['prefix' => 'register'], function() {
    Route::get('/', 'Register@index', 'register', 'login');
    Route::post('/', 'Register@form', 'register', 'login');
});

// Route for Logout controller.
Route::get('/logout', 'Logout@index', 'logout', 'public');
