<?php
/**
 * OptionsLicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class OptionsLicensesManager
 * @author Nicolás Marulanda P.
 */
class OptionsLicensesManager extends ManagerAbstract {
    
    const TABLE                          = 'options_licenses';
    
    const OPTION_LICENSE_CONTROLLER_NAME = 'option_license_controller_name';
    
    const OPTION_LICENSE_METHOD_NAME     = 'option_license_method_name';
    
    const OPTION_LICENSE_CAN_INSERT      = 'option_license_can_insert';
    
    const OPTION_LICENSE_CAN_UPDATE      = 'option_license_can_update';
    
    const OPTION_LICENSE_CAN_DELETE      = 'option_license_can_delete';
    
    const OPTION_LICENSE_FIELDS_NAME     = 'option_license_fields_name';
    
    const LICENSE_ID                     = 'license_id';
    
    public function deleteByLicenseId($licenseId) {
        return parent::deleteByColumn($licenseId, self::LICENSE_ID, \PDO::PARAM_INT);
    }
    
    public function searchAllByLicenseId($licenseId) {
        return parent::searchAllByColumn($licenseId, self::LICENSE_ID, \PDO::PARAM_INT, ['ORDER BY ' . self::COLUMN_ID . ' DESC']);
    }
    
    /**
     * @param array $licensesId
     *
     * @return array
     */
    public function searchAllByLicensesId($licensesId) {
        if (empty($licensesId)) {
            return [];
        }
        
        $where        = array_map(function($licenseId) {
            $param = self::LICENSE_ID . $licenseId;
            parent::addPrepareStatement(self::LICENSE_ID . $licenseId, $licenseId, \PDO::PARAM_INT);
            
            return self::LICENSE_ID . " = :$param";
        }, $licensesId);
        $implodeWhere = implode(' OR ', $where);
        $query        = sprintf('SELECT * FROM %1$s WHERE %2$s', $this->getTableWithPrefix(), $implodeWhere);
        
        return parent::search($query);
    }
    
    public function searchByIdAndLicenseId($id, $licenseId) {
        if (empty($licenseId)) {
            return FALSE;
        }
        
        parent::addPrepareStatement(self::COLUMN_ID, $id, \PDO::PARAM_INT);
        parent::addPrepareStatement(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s ORDER BY %2$s DESC';
        $query = sprintf($query, $this->getTableWithPrefix(), self::COLUMN_ID, self::LICENSE_ID);
        
        return Arrays::findFirst(parent::search($query));
    }
    
    /**
     * @param OptionLicense $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::OPTION_LICENSE_CONTROLLER_NAME, $object->getOptionLicenseControllerName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_LICENSE_METHOD_NAME, $object->getOptionLicenseMethodName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_LICENSE_CAN_INSERT, $object->getOptionLicenseCanInsert(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_LICENSE_CAN_UPDATE, $object->getOptionLicenseCanUpdate(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_LICENSE_CAN_DELETE, $object->getOptionLicenseCanDelete(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::OPTION_LICENSE_FIELDS_NAME, serialize($object->getOptionLicenseFieldsName()), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $optionLicense = new OptionLicense();
        $optionLicense->setId(Arrays::get($result, self::COLUMN_ID));
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
