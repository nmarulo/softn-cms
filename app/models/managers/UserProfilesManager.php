<?php
/**
 * ProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\UserProfile;
use SoftnCMS\util\Arrays;

/**
 * Class ProfilesManager
 * @author Nicolás Marulanda P.
 */
class UserProfilesManager extends CRUDManagerAbstract {
    
    const TABLE                    = 'user_profiles';
    
    const USER_PROFILE_NAME        = 'user_profile_name';
    
    const USER_PROFILE_DESCRIPTION = 'user_profile_description';
    
    /**
     * @param UserProfile $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::USER_PROFILE_NAME, $object->getUserProfileName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::USER_PROFILE_DESCRIPTION, $object->getUserProfileDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $userProfile = new UserProfile();
        $userProfile->setId(Arrays::get($result, self::ID));
        $userProfile->setUserProfileName(Arrays::get($result, self::USER_PROFILE_NAME));
        $userProfile->setUserProfileDescription(Arrays::get($result, self::USER_PROFILE_DESCRIPTION));
        
        return $userProfile;
    }
    
}
