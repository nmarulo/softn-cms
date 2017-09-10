<?php
/**
 * OptionsLicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\util\Arrays;

/**
 * Class OptionsLicensesManager
 * @author Nicolás Marulanda P.
 */
class OptionsLicensesManager extends CRUDManagerAbstract {
    
    const TABLE                          = 'options_licenses';
    
    const OPTION_LICENSE_CONTROLLER_NAME = 'option_license_controller_name';
    
    const OPTION_LICENSE_METHOD_NAME     = 'option_license_method_name';
    
    const OPTION_LICENSE_CAN_INSERT      = 'option_license_can_insert';
    
    const OPTION_LICENSE_CAN_UPDATE      = 'option_license_can_update';
    
    const OPTION_LICENSE_CAN_DELETE      = 'option_license_can_delete';
    
    const OPTION_LICENSE_FIELDS_NAME     = 'option_license_fields_name';
    
    const LICENSE_ID                     = 'license_id';
    
    public function deleteByLicenseId($licenseId) {
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function searchAllByLicenseId($licenseId) {
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::LICENSE_ID);
    }
    
    public function searchAllByLicensesId($licensesId) {
        if (empty($licenseId)) {
            return [];
        }
        
        $where        = array_map(function($licenseId) {
            $param = self::LICENSE_ID . $licenseId;
            parent::parameterQuery($param, $licenseId, \PDO::PARAM_INT);
            
            return self::LICENSE_ID . " = :$param";
        }, $licensesId);
        $implodeWhere = implode(' OR ', $where);
        $query        = sprintf('SELECT * FROM %1$s WHERE %2$s', $this->getTableWithPrefix(), $implodeWhere);
        
        return parent::readData($query);
    }
    
    public function searchByIdAndLicenseId($id, $licenseId) {
        if (empty($licenseId)) {
            return FALSE;
        }
        
        parent::parameterQuery(self::ID, $id, \PDO::PARAM_INT);
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s';
        $query = sprintf($query, $this->getTableWithPrefix(), self::ID, self::LICENSE_ID);
        
        return Arrays::get(parent::readData($query), 0);
    }
    
    /**
     * @param OptionLicense $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::OPTION_LICENSE_CONTROLLER_NAME, $object->getOptionLicenseControllerName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_METHOD_NAME, $object->getOptionLicenseMethodName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_CAN_INSERT, $object->getOptionLicenseCanInsert(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_CAN_UPDATE, $object->getOptionLicenseCanUpdate(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_CAN_DELETE, $object->getOptionLicenseCanDelete(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_FIELDS_NAME, serialize($object->getOptionLicenseFieldsName()), \PDO::PARAM_STR);
        parent::parameterQuery(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $optionLicense = new OptionLicense();
        $optionLicense->setId(Arrays::get($result, self::ID));
        $optionLicense->setOptionLicenseControllerName(Arrays::get($result, self::OPTION_LICENSE_CONTROLLER_NAME));
        $optionLicense->setOptionLicenseMethodName(Arrays::get($result, self::OPTION_LICENSE_METHOD_NAME));
        $optionLicense->setOptionLicenseCanInsert(Arrays::get($result, self::OPTION_LICENSE_CAN_INSERT));
        $optionLicense->setOptionLicenseCanUpdate(Arrays::get($result, self::OPTION_LICENSE_CAN_UPDATE));
        $optionLicense->setOptionLicenseCanDelete(Arrays::get($result, self::OPTION_LICENSE_CAN_DELETE));
        $optionLicense->setOptionLicenseFieldsName(unserialize(Arrays::get($result, self::OPTION_LICENSE_FIELDS_NAME)));
        $optionLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        
        return $optionLicense;
    }
    
}
