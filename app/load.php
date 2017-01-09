<?php

/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */
require $config;
require \ABSPATH . 'define.php';
require \ABSPATH . 'vendor/autoload.php';

use SoftnCMS\controllers\Router;
use SoftnCMS\controllers\Messages;
use SoftnCMS\models\admin\User;
use SoftnCMS\models\Login;
use SoftnCMS\models\admin\Option;

if (\APP_DEBUG === \TRUE) {
    \ini_set('display_errors', \TRUE);
}
\session_start();
Router::setNamespace('default', NAMESPACE_CONTROLLERS_THEME);
Router::setNamespace('login', NAMESPACE_CONTROLLERS);
Router::setNamespace('logout', NAMESPACE_CONTROLLERS);
Router::setNamespace('register', NAMESPACE_CONTROLLERS);
Router::setNamespace('admin', NAMESPACE_CONTROLLERS_ADMIN);
Router::setPath('default', CONTROLLERS_THEME);
Router::setPath('login', CONTROLLERS);
Router::setPath('logout', CONTROLLERS);
Router::setPath('register', CONTROLLERS);
Router::setPath('admin', CONTROLLERS_ADMIN);

if (Login::isLogin()) {
    Router::setDATA(SESSION_USER, User::selectByID(Login::getSession()));
}
Router::setDATA('siteTitle', Option::selectByName('optionTitle')
                                   ->getOptionValue());
Router::setDATA(SITE_URL, Option::selectByName('optionSiteUrl')
                                 ->getOptionValue());
$urlSite = Router::getDATA()['siteUrl'];
$router = new Router();
$router->setEvent('admin', function() {
    if (Router::getRequest()
              ->getRoute() == Router::getRoutes()['admin']
    ) {
        Router::setDATA('menu', require \CONTROLLERS_CONFIG . 'LeftbarController.php');
    }
    
    if (!Login::isLogin()) {
        header('Location: ' . Router::getDATA()['siteUrl']);
        exit();
    }
});
$router->setEvent('login', function() {
    if (Login::isLogin()) {
        header('Location: ' . Router::getDATA()['siteUrl'] . Router::getRoutes()['admin']);
        exit();
    }
});
$router->setEvent('afterCallFunc', function() {
    Router::setDATA('messages', Messages::getMessages());
    Router::setDATA('siteTitle', Option::selectByName('optionTitle')
                                       ->getOptionValue());
});
$router->setEvent('error', function() {
    $key = array_search(Router::getRequest()
                              ->getRoute(), Router::getRoutes(), TRUE);
    
    if ($key === FALSE) {
        $key = 'default';
    }
    
    $route = Router::getRoutes()[$key];
    
    header('Location: ' . Router::getDATA()['siteUrl'] . $route);
    exit();
});
$router->load();
