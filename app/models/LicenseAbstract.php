<?php
/**
 * License.php
 */

namespace SoftnCMS\models;

use SoftnCMS\models\managers\LicensesProfilesManager;
use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\managers\UsersProfilesManager;
use SoftnCMS\models\tables\LicenseProfile;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\models\tables\UserProfile;
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
    
    /** @var array */
    protected $optionsLicenses;
    
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
        $this->route           = $route;
        $this->userId          = $userId;
        $this->optionsLicenses = [];
        $this->licensesId      = [];
        $this->fields          = [];
        $this->setLicenses();
    }
    
    private function setLicenses() {
        $optionsLicensesManager  = new OptionsLicensesManager();
        $licensesProfilesManager = new LicensesProfilesManager();
        $usersProfilesManager    = new UsersProfilesManager();
        $usersProfiles           = $usersProfilesManager->searchAllByUserId($this->userId);
        $profilesId              = array_map(function(UserProfile $userProfile) {
            return $userProfile->getProfileId();
        }, $usersProfiles);
        $licensesProfiles        = $licensesProfilesManager->searchAllByProfilesId($profilesId);
        $this->licensesId        = array_map(function(LicenseProfile $licenseProfile) {
            return $licenseProfile->getLicenseId();
        }, $licensesProfiles);
        $this->optionsLicenses   = $optionsLicensesManager->searchAllByLicensesId($this->licensesId);
    }
    
    //Este método es static porque se usa en "OptionLicenseController".
    
    public static abstract function getManagerClass();
    
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
        $pageName            = $this->route->getControllerName();
        $methodName          = $this->route->getMethodName();
        $optionLicenseObject = array_map(function(OptionLicense $optionLicense) {
            return $optionLicense->getOptionLicenseObject();
        }, $this->optionsLicenses);
        
        $pagesLicense = array_map(function($pagesLicense) use ($pageName, $methodName) {
            return array_filter($pagesLicense, function(PageLicense $pageLicense) use ($pageName, $methodName) {
                return $this->checkMethod($pageLicense, $pageName, $methodName);
            });
        }, $optionLicenseObject);
        
        if (empty(array_filter($pagesLicense))) {
            return FALSE;
        }
        
        $this->setFields($pagesLicense, $methodName);
        
        return TRUE;
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
    
    private function setFields($pagesLicense, $methodName) {
        $this->fields = array_map(function($pagesLicenseValue) use ($methodName) {
            return array_map(function(PageLicense $pageLicense) use ($methodName) {
                return Arrays::get($pageLicense->getMethods(), strtoupper($methodName))
                             ->getFields();
            }, $pagesLicenseValue);
        }, $pagesLicense);
    }
}
