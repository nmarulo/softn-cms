<?php
/**
 * UsersProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\UserProfile;
use SoftnCMS\util\Arrays;

/**
 * Class UsersProfilesManager
 * @author Nicolás Marulanda P.
 */
class UsersProfilesManager extends CRUDManagerAbstract {
    
    const TABLE      = 'user_profiles';
    
    const USER_ID    = 'user_id';
    
    const PROFILE_ID = 'profile_id';
    
    /**
     * @param UserProfile $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::USER_ID, $object->getUserId(), \PDO::PARAM_INT);
        parent::parameterQuery(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $userProfile = new UserProfile();
        $userProfile->setProfileId(Arrays::get($result, self::PROFILE_ID));
        $userProfile->setUserId(Arrays::get($result, self::USER_ID));
        
        return $userProfile;
    }
}
