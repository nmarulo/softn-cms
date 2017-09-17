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
use SoftnCMS\route\Route;
use SoftnCMS\models\LicenseAbstract;
use SoftnCMS\util\Logger;

session_start();

Logger::getInstance()
      ->debug('Inicio de la aplicación.');
$router = new Router();
$router->setEvent(Router::EVENT_INIT_LOAD, function() use ($router) {
    \SoftnCMS\util\Language::load();
    $route               = $router->getRoute();
    $directoryController = $route->getControllerDirectoryName();
    $directoryView       = $route->getDirectoryNameViewController();
    $optionsManager      = new OptionsManager();
    
    if (defined('INSTALL') || $directoryController == 'install') {
        $siteUrl = Router::getSiteURL();
        
        if (file_exists(ABSPATH . 'config.php')) {
            Util::redirect($siteUrl);
        } elseif ($directoryController != Route::CONTROLLER_DIRECTORY_NAME_INSTALL) {
            Logger::getInstance()
                  ->withName('INSTALL')
                  ->debug('Redireccionando a la pagina de instalación.');
            Util::redirect($siteUrl . 'install');
        }
    } elseif ($directoryController == Route::CONTROLLER_DIRECTORY_NAME_ADMIN && !LoginManager::isLogin()) {
        Messages::addWarning(__('Debes iniciar sesión.'), TRUE);
        Util::redirect($optionsManager->getSiteUrl(), 'login');
    } elseif ($directoryController == Route::CONTROLLER_DIRECTORY_NAME_LOGIN && $directoryView == 'index' && LoginManager::isLogin()) {
        Util::redirect($optionsManager->getSiteUrl(), 'admin');
    }
});
$router->setEvent(Router::EVENT_BEFORE_CALL_METHOD, function() use ($router) {
    $route = $router->getRoute();
    
    if ($route->getControllerDirectoryName() == Route::CONTROLLER_DIRECTORY_NAME_ADMIN) {
        $canCallUserFun = LicenseAbstract::initCheck($route, LoginManager::getSession());
        $router->setCanCallUserFunc($canCallUserFun);
        
        //No redirecciona al borrar, porque este método ejecuta mediante AJAX.
        if (!$canCallUserFun && $route->getMethodName() != 'delete' && $route->getMethodName() != 'reloadAJAX') {
            Messages::addDanger(__('No tienes permisos para visualizar esta pagina.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin');
        }
    }
});
$router->load();
