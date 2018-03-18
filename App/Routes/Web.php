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
    Route::get('/', 'Dashboard@index', 'dashboard', 'public');
    // Route for Users controller.
    Route::get('/users', 'Users@index', 'users', 'public');
    
});

// Route for Login controller.
Route::get('/login', 'Login@index', 'login', 'public');

// Route for Register controller.
Route::get('/register', 'Register@index', 'register', 'public');
