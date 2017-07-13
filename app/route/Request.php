<?php
/**
 * Request.php
 */

namespace SoftnCMS\rute;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Sanitize;

/**
 * Class Request
 * @author Nicolás Marulanda P.
 */
class Request {
    
    /** @var Route */
    private $route;
    
    /** @var string */
    private $urlGet;
    
    /** @var array */
    private $urlExplode;
    
    /**
     * Request constructor.
     */
    public function __construct() {
        $this->route      = new Route();
        $this->urlGet     = '';
        $this->urlExplode = [];
        $this->setUrl();
        $this->setRoute();
    }
    
    private function setUrl() {
        $urlGet = Arrays::get($_GET, URL_GET);
        
        if ($urlGet != FALSE) {
            $this->urlGet     = trim(Sanitize::url($urlGet), '/');
            $this->urlExplode = explode('/', $this->urlGet);
        }
    }
    
    private function setRoute() {
        $url = $this->urlExplode;
        $url = $this->setRuteDirectoryController($url);
        
        if (count($this->urlExplode) > 0) {
            $this->setRuteController(Arrays::get($url, 0));
            $positionParam = 1;
            
            if ($this->route->getDirectoryController() != Route::DIRECTORY_THEME) {
                $this->setRuteMethod(Arrays::get($url, 1));
                $positionParam = 2;
            }
            
            $this->setRuteParameter(Arrays::get($url, $positionParam));
            $this->setDirectoryView();
        }
        
        $this->setDirectoryView();
    }
    
    private function setRuteDirectoryController($url) {
        $auxUrl       = $url;
        $urlDirectory = array_shift($url);
        $directory    = Route::DIRECTORY_THEME;
        
        switch ($urlDirectory) {
            case Route::DIRECTORY_ADMIN:
            case Route::DIRECTORY_LOGIN:
            case Route::DIRECTORY_INSTALL:
                $directory = $urlDirectory;
                break;
            default:
                $url = $auxUrl;
                break;
        }
        
        $this->route->setDirectoryController($directory);
        
        return $url;
    }
    
    private function setRuteController($controllerName) {
        if ($controllerName !== FALSE) {
            $controllerSanitize = Sanitize::alphabetic($controllerName);
            //ucfirst(): Convierte el primer carácter en mayúscula.
            $this->route->setController(ucfirst($controllerSanitize));
        }
    }
    
    private function setRuteMethod($methodName) {
        if ($methodName !== FALSE) {
            $methodSanitize = Sanitize::alphabetic($methodName);
            $this->route->setMethod($methodSanitize);
        }
    }
    
    private function setRuteParameter($parameter) {
        if ($parameter !== FALSE) {
            $parameterSanitize = Sanitize::integer($parameter);
            $this->route->setParameter($parameterSanitize);
        }
    }
    
    private function setDirectoryView() {
        $directoryViewController = strtolower($this->route->getController());
        $directoryView           = $this->route->getDirectoryController();
        
        if ($directoryView === 'theme') {
            $optionsManager = new OptionsManager();
            //TODO: Conflicto en el proceso de instalación.
            if (!defined('INSTALL')) {
                $directoryView = $optionsManager->searchByName(OPTION_THEME)
                                                ->getOptionValue();
            }
            $this->route->setViewPath(THEMES);
        }
        
        $this->route->setDirectoryViews($directoryView);
        $this->route->setDirectoryViewsController($directoryViewController);
    }
    
    /**
     * @return string
     */
    public function getUrlGet() {
        return $this->urlGet;
    }
    
    public function getRoute() {
        return $this->route;
    }
}
