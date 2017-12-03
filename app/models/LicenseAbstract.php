<?php
/**
 * License.php
 */

namespace SoftnCMS\models;

use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
abstract class LicenseAbstract implements LicenseInterface {
    
    /** @var LicenseAbstract */
    private static $INSTANCE;
    
    /** @var Router */
    protected $router;
    
    /** @var int */
    protected $userId;
    
    /** @var array */
    protected $optionsLicenses;
    
    /** @var OptionLicense */
    protected $currentOptionLicense;
    
    /** @var array */
    protected $fields;
    
    /** @var bool Es True cuando no existe ningún permiso configurado. */
    private $noLicenseConfigured;
    
    /**
     * License constructor.
     *
     * @param Router $router
     * @param int    $userId
     */
    public function __construct($router, $userId) {
        $this->router               = $router;
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
     * @param Router $router
     * @param int    $userId
     *
     * @return bool True, si tiene algún permiso mayor o igual que 'LICENSE_READ_CODE'
     * o True en caso de no encontrar la clase 'xLicense' correspondiente.
     */
    public static function initCheck($router, $userId) {
        $controllerName   = $router->getRoute()
                                   ->getControllerName();
        $nameSpaceLicense = NAMESPACES_LICENSES . $controllerName . 'License';
        
        if (class_exists($nameSpaceLicense)) {
            self::$INSTANCE = new $nameSpaceLicense($router, $userId);
            
            return self::$INSTANCE->check();
        }
        
        return TRUE;
    }
    
    public function check() {
        $this->setOptionsLicenses($this->getLicensesByUserId($this->userId));
        
        //Si no existe configurado ningún permiso, se permite el acceso total
        return $this->noLicenseConfigured || !empty($this->currentOptionLicense);
    }
    
    /**
     * @param array $licensesId
     */
    private function setOptionsLicenses($licensesId) {
        $pageName                   = $this->router->getRoute()
                                                   ->getControllerName();
        $methodName                 = $this->router->getRoute()
                                                   ->getMethodName();
        $optionsLicensesManager     = new OptionsLicensesManager($this->router->getConnectionDB());
        $this->noLicenseConfigured  = $optionsLicensesManager->count() == 0;
        $this->optionsLicenses      = $optionsLicensesManager->searchAllByLicensesId($licensesId);
        $currentOptionLicense       = array_filter($this->optionsLicenses, function(OptionLicense $optionLicense) use ($pageName, $methodName) {
            return $optionLicense->getOptionLicenseControllerName() == strtoupper($pageName) && $optionLicense->getOptionLicenseMethodName() == strtoupper($methodName);
        });
        $this->currentOptionLicense = Arrays::get(array_merge($currentOptionLicense), 0);
    }
    
    private function getLicensesByUserId($userId) {
        $profilesLicensesManager = new ProfilesLicensesManager($this->router->getConnectionDB());
        $UserLicenses            = $profilesLicensesManager->searchAllByUserId($userId);
        
        return array_map(function(ProfileLicense $userLicense) {
            return $userLicense->getLicenseId();
        }, $UserLicenses);
    }
    
}
