<?php

/*
|--------------------------------------------------------------------------
| # Environment master settings
|--------------------------------------------------------------------------
*/

return [
        'debug'             => TRUE,
        'app_key'           => 'mysupersecurekey',//TODO: establecer key
        'port'              => FALSE,
        'api_debug'         => TRUE,
        'api_version'       => '0.5.0',
        'api_request_limit' => 25,
        'token_id'          => '4f1g23a12aa',//TODO: establecer id


    'middlewares'   => [

    ],

    'routes' => [
    
    ],

    'databases' => [
        'on' => true,
        'default' => 'local',
        'local'   => [
            'service'       => true,
            'driver'        => 'mysql',
            'hostname'      => 'localhost',
            'username'      => 'root',
            'password'      => 'root',
            'basename'      => 'softn_cms_framework',
            'limit_request' => 25,
        ]
    ],

    'mail' => [
        'service' => false,
        'email'   => 'your@email.test',
        'name'    => 'Your Name',
    ],

];
