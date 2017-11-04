<?php

/**
 * Fichero de dirección de la aplicación y carga de ficheros.
 * Fichero que direcciona al usuario hacia el panel de administración
 * o hacia la plantilla y carga los ficheros necesarios.
 */
require ABSPATH . 'define.php';
require ABSPATH . 'vendor/autoload.php';

use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\models\template\MenuTemplate;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\MenusManager;
use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\managers\UsersManager;
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
$router->setEvent(Router::EVENT_ERROR, function() {
    Util::redirect(Router::getSiteURL());
});
$router->setEvent(Router::EVENT_INIT_LOAD, function() use ($router) {
    \SoftnCMS\util\Language::load();
    $route               = $router->getRoute();
    $directoryController = $route->getControllerDirectoryName();
    $directoryView       = $route->getDirectoryNameViewController();
    $siteUrl             = Router::getSiteURL();
    $isInstall           = defined('INSTALL') || $directoryController == 'install';
    
    if ($isInstall) {
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
        Util::redirect($siteUrl, 'login');
    } elseif ($directoryController == Route::CONTROLLER_DIRECTORY_NAME_LOGIN && $directoryView == 'index' && LoginManager::isLogin()) {
        Util::redirect($siteUrl, 'admin');
    }
    
    ViewController::setViewDataBase(function() use ($directoryController, $isInstall, $siteUrl) {
        if ($isInstall) {
            return [
                'siteUrl'   => $siteUrl,
                'siteTitle' => 'SoftN CMS',
            ];
        }
        
        $optionsManager = new OptionsManager();
        $user           = NULL;
        $menuList       = [];
        $sidebars       = [];
        
        if ($directoryController == Route::CONTROLLER_DIRECTORY_NAME_THEME) {
            $menusManager    = new MenusManager();
            $optionMenu      = $optionsManager->searchByName(OptionConstants::MENU);
            $menu            = $menusManager->searchById($optionMenu->getOptionValue());
            $menuTemplate    = new MenuTemplate($menu, TRUE);
            $menuList        = $menuTemplate->getSubMenuList();
            $sidebarsManager = new SidebarsManager();
            $sidebars        = $sidebarsManager->searchAll();
        }
        
        if (LoginManager::checkSession()) {
            $usersManager = new UsersManager();
            $user         = $usersManager->searchById(LoginManager::getSession());
            
            if (empty($user)) {
                Util::redirect(Router::getSiteURL(), 'login/logout');
            }
        }
        
        return [
            'siteUrl'     => $siteUrl,
            'siteTitle'   => $optionsManager->searchByName(OptionConstants::SITE_TITLE)
                                            ->getOptionValue(),
            'userSession' => $user,
            'menuList'    => $menuList,
            'sidebars'    => $sidebars,
        ];
    });
});
$router->setEvent(Router::EVENT_BEFORE_CALL_METHOD, function() use ($router) {
    $route = $router->getRoute();
    
    if ($route->getControllerDirectoryName() == Route::CONTROLLER_DIRECTORY_NAME_ADMIN) {
        $canCallUserFun = LicenseAbstract::initCheck($route, LoginManager::getSession());
        $router->setCanCallUserFunc($canCallUserFun);
        
        //TODO: Crear una implementación que permita saber cuando se realiza una llamada desde AJAX.
        //No redirecciona al borrar, porque este método ejecuta mediante AJAX.
        if (!$canCallUserFun && $route->getMethodName() != 'delete' && $route->getMethodName() != 'reload') {
            Messages::addDanger(__('No tienes permisos para visualizar esta pagina.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin');
        }
    }
});
$router->load();
