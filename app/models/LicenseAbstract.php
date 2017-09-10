<?php
/**
 * License.php
 */

namespace SoftnCMS\models;

use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
abstract class LicenseAbstract implements LicenseInterface {
    
    /** @var LicenseAbstract */
    private static $INSTANCE;
    
    /** @var Route */
    protected $route;
    
    /** @var int */
    protected $userId;
    
    /** @var array */
    protected $optionsLicenses;
    
    /** @var OptionLicense */
    protected $currentOptionLicense;
    
    /** @var array */
    protected $fields;
    
    /**
     * License constructor.
     *
     * @param Route $route
     * @param int   $userId
     */
    public function __construct($route, $userId) {
        $this->route                = $route;
        $this->userId               = $userId;
        $this->currentOptionLicense = NULL;
        $this->optionsLicenses      = [];
        $this->fields               = [];
    }
    
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
        $this->setOptionsLicenses($this->getLicensesByUserId($this->userId), $this->route);
        
        //Si no existe configurado ningún permiso, se permite el acceso total
        return empty($this->optionsLicenses) || !empty($this->currentOptionLicense);
    }
    
    /**
     * @param array $licensesId
     * @param Route $route
     */
    private function setOptionsLicenses($licensesId, $route) {
        $pageName                   = $route->getControllerName();
        $methodName                 = $route->getMethodName();
        $optionsLicensesManager     = new OptionsLicensesManager();
        $this->optionsLicenses      = $optionsLicensesManager->searchAllByLicensesId($licensesId);
        $currentOptionLicense       = array_filter($this->optionsLicenses, function(OptionLicense $optionLicense) use ($pageName, $methodName) {
            return $optionLicense->getOptionLicenseControllerName() == strtoupper($pageName) && $optionLicense->getOptionLicenseMethodName() == strtoupper($methodName);
        });
        $this->currentOptionLicense = Arrays::get(array_merge($currentOptionLicense), 0);
    }
    
    private function getLicensesByUserId($userId) {
        $profilesLicensesManager = new ProfilesLicensesManager();
        $UserLicenses            = $profilesLicensesManager->searchAllByUserId($userId);
        
        return array_map(function(ProfileLicense $userLicense) {
            return $userLicense->getLicenseId();
        }, $UserLicenses);
    }
    
}
