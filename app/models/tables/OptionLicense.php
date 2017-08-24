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
    
    /** @var string */
    private $optionLicenseType;
    
    /** @var string */
    private $optionLicenseController;
    
    /** @var string */
    private $optionLicenseMethod;
    
    /** @var string */
    private $optionLicenseTable;
    
    /** @var string */
    private $optionLicenseColumn;
    
    /** @var int */
    private $optionLicenseDataId;
    
    /** @var int */
    private $licenseId;
    
    /**
     * @return int
     */
    public function getOptionLicenseDataId() {
        return $this->optionLicenseDataId;
    }
    
    /**
     * @param int $optionLicenseDataId
     */
    public function setOptionLicenseDataId($optionLicenseDataId) {
        $this->optionLicenseDataId = $optionLicenseDataId;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseType() {
        return $this->optionLicenseType;
    }
    
    /**
     * @param string $optionLicenseType
     */
    public function setOptionLicenseType($optionLicenseType) {
        $this->optionLicenseType = $optionLicenseType;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseController() {
        return $this->optionLicenseController;
    }
    
    /**
     * @param string $optionLicenseController
     */
    public function setOptionLicenseController($optionLicenseController) {
        $this->optionLicenseController = $optionLicenseController;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseMethod() {
        return $this->optionLicenseMethod;
    }
    
    /**
     * @param string $optionLicenseMethod
     */
    public function setOptionLicenseMethod($optionLicenseMethod) {
        $this->optionLicenseMethod = $optionLicenseMethod;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseTable() {
        return $this->optionLicenseTable;
    }
    
    /**
     * @param string $optionLicenseTable
     */
    public function setOptionLicenseTable($optionLicenseTable) {
        $this->optionLicenseTable = $optionLicenseTable;
    }
    
    /**
     * @return string
     */
    public function getOptionLicenseColumn() {
        return $this->optionLicenseColumn;
    }
    
    /**
     * @param string $optionLicenseColumn
     */
    public function setOptionLicenseColumn($optionLicenseColumn) {
        $this->optionLicenseColumn = $optionLicenseColumn;
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
