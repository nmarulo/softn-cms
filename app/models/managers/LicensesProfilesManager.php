<?php
/**
 * LicensesProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\LicenseProfile;
use SoftnCMS\util\Arrays;

/**
 * Class LicensesProfilesManager
 * @author Nicolás Marulanda P.
 */
class LicensesProfilesManager extends CRUDManagerAbstract {
    
    const TABLE      = 'licenses_profiles';
    
    const LICENSE_ID = 'license_id';
    
    const PROFILE_ID = 'profile_id';
    
    /**
     * @param LicenseProfile $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_INT);
        parent::parameterQuery(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $licenseProfile = new LicenseProfile();
        $licenseProfile->setProfileId(Arrays::get($result, self::PROFILE_ID));
        $licenseProfile->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        
        return $licenseProfile;
    }
}
