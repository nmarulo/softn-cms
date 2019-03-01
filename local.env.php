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
        'api_base_uri'      => 'api',
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
    
    //TODO: obtener datos desde base de datos.
    'php_mailer' => [
            'activated'    => FALSE,
            // Enable verbose debug output
            'smtp_debug'   => 0,
            // Specify main and backup SMTP servers
            'host'         => 'smtp.gmail.com',
            // Enable SMTP authentication
            'smtp_auth'    => TRUE,
            // SMTP username
            'username'     => 'test.softn@gmail.com',
            // SMTP password
            'password'     => 'password',
            // Enable TLS encryption, `ssl` also accepted
            'smtp_secure'  => 'tls',
            'port'         => 587,
            'from_name'    => 'Softn CMS',
            'from_address' => 'no-reply@softn.red',
    ],
];
