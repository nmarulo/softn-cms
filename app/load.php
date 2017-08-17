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
    \SoftnCMS\util\Language::load();
    $route               = $router->getRoute();
    $directoryController = $route->getControllerDirectoryName();
    $directoryView       = $route->getDirectoryNameViewController();
    $optionsManager      = new OptionsManager();
    
    if (defined('INSTALL') || $directoryController == 'install') {
        $siteUrl = $optionsManager->getSiteUrl($router);
        
        if (file_exists(ABSPATH . 'config.php')) {
            Util::redirect($siteUrl);
        } elseif ($directoryController != 'install') {
            Util::redirect($siteUrl . 'install');
        }
    } elseif ($directoryController == 'admin' && !LoginManager::isLogin()) {
        Messages::addWarning(__('Debes iniciar sesión.'), TRUE);
        Util::redirect($optionsManager->getSiteUrl(), 'login');
    } elseif ($directoryController == 'login' && $directoryView == 'index' && LoginManager::isLogin()) {
        Util::redirect($optionsManager->getSiteUrl(), 'admin');
    }
});
$router->load();
