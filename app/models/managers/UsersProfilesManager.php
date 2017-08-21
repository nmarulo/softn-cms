<?php
/**
 * UsersProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\UserProfile;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class UsersProfilesManager
 * @author Nicolás Marulanda P.
 */
class UsersProfilesManager extends CRUDManagerAbstract {
    
    const TABLE      = 'users_profiles';
    
    const USER_ID    = 'user_id';
    
    const PROFILE_ID = 'profile_id';
    
    public function searchAllByUserId($userId) {
        parent::parameterQuery(self::USER_ID, $userId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::USER_ID);
    }
    
    public function deleteAllByUserId($userId) {
        parent::parameterQuery(self::USER_ID, $userId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllProfilesByUserId($profilesId, $userId) {
        $mySql = new MySQL();
        $queryProfilesId = '';
        parent::parameterQuery(self::USER_ID, $userId, \PDO::PARAM_INT);
        array_walk($profilesId, function($profileId) use (&$queryProfilesId) {
            $param = self::PROFILE_ID . $profileId;
            parent::parameterQuery($param, $profileId, \PDO::PARAM_INT, self::PROFILE_ID);
            $queryProfilesId .= empty($queryProfilesId) ? '' : ' OR ';
            $queryProfilesId .= self::PROFILE_ID . " = :$param";
        });
        
        $query = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::USER_ID, $queryProfilesId);
        $result = $mySql->delete($query, $this->prepare);
        parent::closeConnection($mySql);
        
        return $result;
    }
    
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
