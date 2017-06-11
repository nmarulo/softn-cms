<?php

/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */
require ABSPATH . 'define.php';
require ABSPATH . 'vendor/autoload.php';

use SoftnCMS\rute\Router;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\util\Util;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Messages;

session_start();

$router = new Router();
$router->setEvent(Router::EVENT_INIT_LOAD, function() use ($router) {
    $route               = $router->getRoute();
    $directoryController = $route->getDirectoryController();
    $directoryView       = $route->getDirectoryViewController();
    $optionsManager      = new OptionsManager();
    
    if ($directoryController == 'admin' && !LoginManager::isLogin()) {
        Messages::addSessionMessage('Debes iniciar sesión.', Messages::TYPE_WARNING);
        Util::redirect($optionsManager->getSiteUrl(), 'login');
    } elseif ($directoryController == 'login' && $directoryView == 'index' && LoginManager::isLogin()) {
        Util::redirect($optionsManager->getSiteUrl(), 'admin');
    }
});

$router->load();
