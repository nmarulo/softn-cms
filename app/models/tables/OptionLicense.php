<?php
/**
 * OptionLicense.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\util\database\TableAbstract;

/**
 * Class OptionLicense
 * @author Nicolás Marulanda P.
 */
class OptionLicense extends TableAbstract {
    
    /** @var string */
    private $optionLicenseControllerName;
    
    /** @var string */
    private $optionLicenseMethodName;
    
    /** @var int */
    private $optionLicenseCanInsert;
    
    /** @var int */
    private $optionLicenseCanUpdate;
    
    /** @var int */
    private $optionLicenseCanDelete;
    
    /** @var array */
    private $optionLicenseFieldsName;
    
    /** @var int */
    private $licenseId;
    
    /**
     * OptionLicense constructor.
     */
    public function __construct() {
        $this->optionLicenseFieldsName = [];
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseControllerName() {
        return $this->optionLicenseControllerName;
    }
    
    /**
     * @param string $optionLicenseControllerName
     */
    public function setOptionLicenseControllerName($optionLicenseControllerName) {
        $this->optionLicenseControllerName = $optionLicenseControllerName;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseMethodName() {
        return $this->optionLicenseMethodName;
    }
    
    /**
     * @param string $optionLicenseMethodName
     */
    public function setOptionLicenseMethodName($optionLicenseMethodName) {
        $this->optionLicenseMethodName = $optionLicenseMethodName;
    }
    
    /**
     * @return int
     */
    public function getOptionLicenseCanInsert() {
        return $this->optionLicenseCanInsert;
    }
    
    /**
     * @param int $optionLicenseCanInsert
     */
    public function setOptionLicenseCanInsert($optionLicenseCanInsert) {
        $this->optionLicenseCanInsert = $optionLicenseCanInsert;
    }
    
    /**
     * @return int
     */
    public function getOptionLicenseCanUpdate() {
        return $this->optionLicenseCanUpdate;
    }
    
    /**
     * @param int $optionLicenseCanUpdate
     */
    public function setOptionLicenseCanUpdate($optionLicenseCanUpdate) {
        $this->optionLicenseCanUpdate = $optionLicenseCanUpdate;
    }
    
    /**
     * @return int
     */
    public function getOptionLicenseCanDelete() {
        return $this->optionLicenseCanDelete;
    }
    
    /**
     * @param int $optionLicenseCanDelete
     */
    public function setOptionLicenseCanDelete($optionLicenseCanDelete) {
        $this->optionLicenseCanDelete = $optionLicenseCanDelete;
    }
    
    /**
     * @return array
     */
    public function getOptionLicenseFieldsName() {
        return $this->optionLicenseFieldsName;
    }
    
    /**
     * @param array $optionLicenseFieldsName
     */
    public function setOptionLicenseFieldsName($optionLicenseFieldsName) {
        $this->optionLicenseFieldsName = $optionLicenseFieldsName;
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
