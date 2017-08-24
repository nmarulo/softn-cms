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
    
    const OPTION_LICENSE_DATA_ID    = 'option_license_data_id';
    
    const LICENSE_ID                = 'license_id';
    
    /**
     * @param OptionLicense $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::OPTION_LICENSE_TYPE, $object->getOptionLicenseType(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_CONTROLLER, $object->getOptionLicenseController(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_METHOD, $object->getOptionLicenseMethod(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_TABLE, $object->getOptionLicenseTable(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_COLUMN, $object->getOptionLicenseColumn(), \PDO::PARAM_STR);
        parent::parameterQuery(self::OPTION_LICENSE_DATA_ID, $object->getOptionLicenseDataId(), \PDO::PARAM_INT);
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
        $optionLicense->setOptionLicenseDataId(Arrays::get($result, self::OPTION_LICENSE_DATA_ID));
        $optionLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        
        return $optionLicense;
    }
    
}
