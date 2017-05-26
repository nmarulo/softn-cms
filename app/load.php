<?php

/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */
require ABSPATH . 'define.php';
require ABSPATH . 'vendor/autoload.php';

use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\Login;
use SoftnCMS\models\admin\Option;
use SoftnCMS\helpers\Helps;

session_start();

$router = new Router();
// Configuraciones =========================================
Router::setNamespace('default', NAMESPACE_CONTROLLERS_THEME);
Router::setNamespace('login', NAMESPACE_CONTROLLERS);
Router::setNamespace('logout', NAMESPACE_CONTROLLERS);
Router::setNamespace('register', NAMESPACE_CONTROLLERS);
Router::setNamespace('admin', NAMESPACE_CONTROLLERS_ADMIN);
Router::setNamespace('install', NAMESPACE_CONTROLLERS);
Router::setPath('default', CONTROLLERS_THEME);
Router::setPath('login', CONTROLLERS);
Router::setPath('logout', CONTROLLERS);
Router::setPath('register', CONTROLLERS);
Router::setPath('admin', CONTROLLERS_ADMIN);
Router::setPath('install', CONTROLLERS);
Router::setViewPath('default', VIEWS);
Router::setViewPath('admin', VIEWS_ADMIN);
Router::setViewPath('theme', THEMES);

if (defined('INSTALL')) {
    Router::setDATA(SITE_TITLE, 'Proceso de instalación');
    Router::setDATA(SITE_URL, Router::getRequest()
                                    ->getUrl());
} else {
    if (Login::isLogin()) {
        Router::setDATA(SESSION_USER, User::selectByID(Login::getSession()));
    }
    
    Router::setDATA(SITE_TITLE, Option::selectByName('optionTitle')
                                      ->getOptionValue());
    Router::setDATA(SITE_URL, Option::selectByName('optionSiteUrl')
                                    ->getOptionValue());
}
// Eventos =================================================
$router->setEvent('admin', function() {
    if (Router::getRequest()
              ->getRoute() == Router::getRoutes()['admin']
    ) {
        Router::setDATA('menu', require CONTROLLERS_CONFIG . 'LeftbarController.php');
    }
    
    if (!Login::isLogin()) {
        Helps::redirect();
    }
});
$router->setEvent('login', function() {
    if (Login::isLogin()) {
        Helps::redirect(Router::getRoutes()['admin']);
    }
});
$router->setEvent('beforeCallView', function() {
    Router::setDATA('messages', Messages::getMessages());
    if (defined('INSTALL')) {
        Router::setDATA(SITE_TITLE, 'Proceso de instalación.');
    } else {
        Router::setDATA(SITE_TITLE, Option::selectByName('optionTitle')
                                          ->getOptionValue());
        
    }
});
$router->setEvent('beforeCheckRoute', function() {
    if (defined('INSTALL') && Router::getRequest()
                                    ->getRoute() != Router::getRoutes()['install']
    ) {
        Helps::redirect(Router::getRoutes()['install']);
    }
});
$router->setEvent('install', function() {
    if (!defined('INSTALL')) {
        Helps::redirect(Router::getRoutes()['login']);
    }
});
$router->setEvent('error', function() {
    Helps::redirect();
});
//Carga la pagina.
$router->load();
