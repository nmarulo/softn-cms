<?php
/**
 * LicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\License;
use SoftnCMS\util\Arrays;

/**
 * Class LicensesManager
 * @author Nicolás Marulanda P.
 */
class LicensesManager extends CRUDManagerAbstract {
    
    const TABLE               = 'licenses';
    
    const LICENSE_NAME        = 'license_name';
    
    const LICENSE_DESCRIPTION = 'license_description';
    
    /**
     * @param License $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::LICENSE_NAME, $object->getLicenseName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::LICENSE_DESCRIPTION, $object->getLicenseDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $license = new License();
        $license->setId(Arrays::get($result, self::ID));
        $license->setLicenseDescription(Arrays::get($result, self::LICENSE_DESCRIPTION));
        $license->setLicenseName(Arrays::get($result, self::LICENSE_NAME));
        
        return $license;
    }
    
}
