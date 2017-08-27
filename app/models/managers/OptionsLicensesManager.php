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
    
    const TABLE                     = 'options_licenses';
    
    const OPTION_LICENSE_TYPE       = 'option_license_type';
    
    const OPTION_LICENSE_CONTROLLER = 'option_license_controller';
    
    const OPTION_LICENSE_METHOD     = 'option_license_method';
    
    const OPTION_LICENSE_TABLE      = 'option_license_table';
    
    const OPTION_LICENSE_COLUMN     = 'option_license_column';
    
    const LICENSE_ID                = 'license_id';
    
    public function searchAll($controllerName, $methodName, $userId, $licenseType = LICENSE_READ_CODE, $tableName = '', $columnName = '') {
        $queryTableColumn = '';
        parent::parameterQuery(self::OPTION_LICENSE_CONTROLLER, $controllerName, \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_METHOD, $methodName, \PDO::PARAM_STR);
        parent::parameterQuery(UsersProfilesManager::USER_ID, $userId, \PDO::PARAM_INT);
        parent::parameterQuery(self::OPTION_LICENSE_TYPE, $licenseType, \PDO::PARAM_INT);
        
        if (!empty($tableName)) {
            parent::parameterQuery(self::OPTION_LICENSE_TABLE, $tableName, \PDO::PARAM_STR);
            $queryTableColumn = sprintf(' AND %1$s = :%1$s', self::OPTION_LICENSE_TABLE);
        }
        
        if (!empty($columnName)) {
            parent::parameterQuery(self::OPTION_LICENSE_COLUMN, $columnName, \PDO::PARAM_STR);
            $queryTableColumn .= sprintf(' AND %1$s = :%1$s', self::OPTION_LICENSE_COLUMN);
        }
        
        $table                 = $this->getTableWithPrefix();
        $tableLicensesProfiles = parent::getTableWithPrefix(LicensesProfilesManager::TABLE);
        $tableUsersProfiles    = parent::getTableWithPrefix(UsersProfilesManager::TABLE);
        $query                 = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s = :%3$s AND %11$s >= :%11$s%12$s AND %1$s.%4$s IN (SELECT %5$s FROM %6$s WHERE %6$s.%7$s IN (SELECT %8$s FROM %9$s WHERE %10$s = :%10$s))';
        $query                 = sprintf($query, $table, self::OPTION_LICENSE_CONTROLLER, self::OPTION_LICENSE_METHOD, self::LICENSE_ID, LicensesProfilesManager::LICENSE_ID, $tableLicensesProfiles, LicensesProfilesManager::PROFILE_ID, UsersProfilesManager::PROFILE_ID, $tableUsersProfiles, UsersProfilesManager::USER_ID, self::OPTION_LICENSE_TYPE, $queryTableColumn);
        
        return parent::readData($query);
    }
    
    public function searchAllByMethodAndLicenseType($methodName, $licenseType) {
        parent::parameterQuery(self::OPTION_LICENSE_TYPE, $licenseType, \PDO::PARAM_INT);
        parent::parameterQuery(self::OPTION_LICENSE_METHOD, $methodName, \PDO::PARAM_STR);
        
        $query = 'SELECT * FROM %1$s WHERE %2$s = :%2$s AND %3$s >= :%3$s';
        $query = sprintf($query, $this->getTableWithPrefix(), self::OPTION_LICENSE_METHOD, self::OPTION_LICENSE_TYPE);
        
        return parent::readData($query);
    }
    
    /**
     * @param OptionLicense $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::OPTION_LICENSE_TYPE, $object->getOptionLicenseType(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_CONTROLLER, $object->getOptionLicenseController(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_METHOD, $object->getOptionLicenseMethod(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_TABLE, $object->getOptionLicenseTable(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_COLUMN, $object->getOptionLicenseColumn(), \PDO::PARAM_STR);
        parent::parameterQuery(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $optionLicense = new OptionLicense();
        $optionLicense->setId(Arrays::get($result, self::ID));
        $optionLicense->setOptionLicenseType(Arrays::get($result, self::OPTION_LICENSE_TYPE));
        $optionLicense->setOptionLicenseController(Arrays::get($result, self::OPTION_LICENSE_CONTROLLER));
        $optionLicense->setOptionLicenseMethod(Arrays::get($result, self::OPTION_LICENSE_METHOD));
        $optionLicense->setOptionLicenseTable(Arrays::get($result, self::OPTION_LICENSE_TABLE));
        $optionLicense->setOptionLicenseColumn(Arrays::get($result, self::OPTION_LICENSE_COLUMN));
        $optionLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        
        return $optionLicense;
    }
    
}
