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
    
    const TABLE                 = 'options_licenses';
    
    const OPTION_LICENSE_OBJECT = 'option_license_object';
    
    const LICENSE_ID            = 'license_id';
    
    public function searchAllByLicensesId($licensesId) {
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
        parent::parameterQuery(self::OPTION_LICENSE_OBJECT, serialize($object->getOptionLicenseObject()), \PDO::PARAM_STR);
        parent::parameterQuery(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $optionLicense = new OptionLicense();
        $optionLicense->setId(Arrays::get($result, self::ID));
        $optionLicense->setOptionLicenseObject(unserialize(Arrays::get($result, self::OPTION_LICENSE_OBJECT)));
        $optionLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        
        return $optionLicense;
    }
    
}
