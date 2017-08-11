<?php
/**
 * LicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\UserLicense;
use SoftnCMS\util\Arrays;

/**
 * Class LicensesManager
 * @author Nicolás Marulanda P.
 */
class UserLicensesManager extends CRUDManagerAbstract {
    
    const TABLE                    = 'user_licenses';
    
    const USER_LICENSE_NAME        = 'user_license_name';
    
    CONST USER_LICENSE_DESCRIPTION = 'user_license_description';
    
    /**
     * @param UserLicense $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::USER_LICENSE_NAME, $object->getUserLicenseName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_LICENSE_DESCRIPTION, $object->getUserLicenseDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        
        $userLicense = new UserLicense();
        $userLicense->setId(Arrays::get($result, self::ID));
        $userLicense->setUserLicenseName(Arrays::get($result, self::USER_LICENSE_NAME));
        $userLicense->setUserLicenseDescription(Arrays::get($result, self::USER_LICENSE_DESCRIPTION));
        
        return $userLicense;
    }
    
}
