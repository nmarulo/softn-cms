<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace App\Models;

use Silver\Database\Model;

/**
 * @property int    $id
 * @property string $user_login
 * @property string $user_name
 * @property string $user_email
 * @property string $user_registered
 * @property string $user_password
 */
class Users extends Model {
    
    protected static $_table     = 'users';
    
    protected static $_primary   = 'id';
    
    protected        $hidden     = [
            'user_password',
    ];
    
    protected        $searchable = [
            'user_login',
            'user_name',
            'user_email',
            'user_registered',
    ];
    
}
