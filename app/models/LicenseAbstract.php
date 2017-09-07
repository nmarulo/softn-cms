<?php
/**
 * License.php
 */

namespace SoftnCMS\models;

use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\managers\UsersLicensesManager;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\models\tables\UserLicense;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
abstract class LicenseAbstract {
    
    /** @var LicenseAbstract */
    private static $INSTANCE;
    
    /** @var Route */
    protected $route;
    
    /** @var int */
    protected $userId;
    
    /** @var PageLicense */
    protected $pageLicense;
    
    /** @var array */
    protected $licensesId;
    
    /** @var array */
    protected $fields;
    
    /**
     * License constructor.
     *
     * @param Route $route
     * @param int   $userId
     */
    public function __construct($route, $userId) {
        $this->route       = $route;
        $this->userId      = $userId;
        $this->pageLicense = NULL;
        $this->licensesId  = [];
        $this->fields      = [];
    }
    
    public static abstract function getManagerClass();//Este método es static porque se usa en "OptionLicenseController".
    
    /**
     * @return LicenseAbstract
     */
    public static function getInstance() {
        return self::$INSTANCE;
    }
    
    /**
     * @param Route $route
     * @param int   $userId
     *
     * @return bool True, si tiene algún permiso mayor o igual que 'LICENSE_READ_CODE'
     * o True en caso de no encontrar la clase 'xLicense' correspondiente.
     */
    public static function initCheck($route, $userId) {
        $controllerName   = $route->getControllerName();
        $nameSpaceLicense = NAMESPACES_LICENSES . $controllerName . 'License';
        
        if (class_exists($nameSpaceLicense)) {
            self::$INSTANCE = new $nameSpaceLicense($route, $userId);
            
            return self::$INSTANCE->check();
        }
        
        return TRUE;
    }
    
    public function check() {
        $this->setLicenses($this->userId);
        $this->setPageLicense($this->licensesId, $this->route);
        
        return !empty($this->pageLicense);
    }
    
    private function setLicenses($userId) {
        $usersLicensesManager = new UsersLicensesManager();
        $UserLicenses         = $usersLicensesManager->searchAllByUserId($userId);
        $this->licensesId     = array_map(function(UserLicense $userLicense) {
            return $userLicense->getLicenseId();
        }, $UserLicenses);
    }
    
    /**
     * @param array $licensesId
     * @param Route $route
     */
    private function setPageLicense($licensesId, $route) {
        $pageName               = $route->getControllerName();
        $methodName             = $route->getMethodName();
        $optionsLicensesManager = new OptionsLicensesManager();
        $optionsLicenses        = $optionsLicensesManager->searchAllByLicensesId($licensesId);
        
        array_walk($optionsLicenses, function(OptionLicense $optionLicense) use ($pageName, $methodName) {
            array_walk($optionLicense->getOptionLicenseObject(), function(PageLicense $pageLicense) use ($pageName, $methodName) {
                if ($this->checkMethod($pageLicense, $pageName, $methodName)) {
                    if ($this->pageLicense == NULL) {
                        $this->pageLicense = new PageLicense($pageLicense->getPageName());
                    }
                    
                    $this->pageLicense->addOrUpdateMethod(Arrays::get($pageLicense->getMethods(), strtoupper($methodName)));
                }
            });
        });
    }
    
    /**
     * @param PageLicense $pageLicense
     * @param string      $pageName
     * @param string      $methodName
     *
     * @return bool
     */
    private function checkMethod($pageLicense, $pageName, $methodName) {
        $strcasecmp = strcasecmp($pageLicense->getPageName(), $pageName);
        
        return $strcasecmp == 0 && Arrays::keyExists($pageLicense->getMethods(), strtoupper($methodName));
    }
    
}
