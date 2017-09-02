<?php
/**
 * OptionLicense.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class OptionLicense
 * @author Nicolás Marulanda P.
 */
class OptionLicense extends TableAbstract {
    
    /** @var array */
    private $optionLicenseObject;
    
    /** @var int */
    private $licenseId;
    
    /**
     * OptionLicense constructor.
     */
    public function __construct() {
        $this->optionLicenseObject = [];
        $this->licenseId           = 0;
    }
    
    /**
     * @return array
     */
    public function getOptionLicenseObject() {
        return $this->optionLicenseObject;
    }
    
    /**
     * @param array $optionLicenseObject
     */
    public function setOptionLicenseObject($optionLicenseObject) {
        $this->optionLicenseObject = $optionLicenseObject;
    }
    
    /**
     * @return int
     */
    public function getLicenseId() {
        return $this->licenseId;
    }
    
    /**
     * @param int $licenseId
     */
    public function setLicenseId($licenseId) {
        $this->licenseId = $licenseId;
    }
    
}
