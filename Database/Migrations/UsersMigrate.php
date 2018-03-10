<?php

/**
 * SilverEngine  - PHP MVC framework
 * @package   SilverEngine
 * @author    SilverEngine Team
 * @copyright 2015-2017
 * @license   MIT
 * @link      https://github.com/SilverEngine/Framework
 */

namespace Database\Migrations;

use Silver\Database\Parts\Raw;
use Silver\Database\Query;

class UsersMigrate {
    
    protected static $table = 'users';
    
    public static function up() {
        Query::drop(static::$table)
             ->ifExists()
             ->execute();
        
        Query::create(static::$table, function($query) {
            $query->integer('id')
                  ->primary()
                  ->autoincrement();
            $query->varchar("user_login", 60);
            $query->varchar("user_name", 50);
            $query->varchar("user_password", 64);
            $query->varchar("user_email", 100);
            $query->datetime('user_registered')
                  ->default(new Raw('CURRENT_TIMESTAMP'));
        })
             ->execute();
    }
    
    public static function down() {
        Query::drop(static::$table)
             ->ifExists()
             ->execute();
    }
    
}
