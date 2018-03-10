<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace Database\Seeds;

use Silver\Database\Query as Seed;

class DefaultSeed {
    
    public static $table;
    
    /**
     * Run the database seeds.
     * @return void
     */
    public static function run() {
        self::Users();
    }
    
    public static function Users($table = 'users') {
        Seed::insert($table, [
            'user_login'    => 'admin',
            'user_name'     => 'Administrator',
            'user_password' => 'admin',
            'user_email'    => 'info@softn.red',
        ])
            ->execute();
    }
}
