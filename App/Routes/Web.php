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
    });
});

// Route for Login controller.
Route::group(['prefix' => 'login'], function() {
    Route::get('/', 'Login@index', 'login', 'public');
    Route::post('/', 'Login@form', 'login', 'public');
});

// Route for Register controller.
Route::group(['prefix' => 'register'], function() {
    Route::get('/', 'Register@index', 'register', 'public');
    Route::post('/', 'Register@form', 'register', 'public');
});

// Route for Logout controller.
Route::get('/logout', 'Logout@index', 'logout', 'dashboard');
