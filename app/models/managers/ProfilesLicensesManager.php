<?php
/**
 * LicensesProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class LicensesProfilesManager
 * @author Nicolás Marulanda P.
 */
class ProfilesLicensesManager extends CRUDManagerAbstract {
    
    const TABLE      = 'profiles_licenses';
    
    const LICENSE_ID = 'license_id';
    
    const PROFILE_ID = 'profile_id';
    
    public function searchAllByUserId($userId) {
        parent::parameterQuery(UsersManager::ID, $userId, \PDO::PARAM_INT);
        $tableUsers = parent::getTableWithPrefix(UsersManager::TABLE);
        $query      = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s.%4$s FROM %3$s WHERE %5$s = :%5$s)';
        $query      = sprintf($query, $this->getTableWithPrefix(), self::PROFILE_ID, $tableUsers, UsersManager::PROFILE_ID, UsersManager::ID);
        
        return parent::readData($query);
    }
    
    public function searchAllByProfileId($profileId) {
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::PROFILE_ID);
    }
    
    public function searchAllByLicenseId($licenseId) {
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::LICENSE_ID);
    }
    
    public function deleteAllByProfileId($profileId) {
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllByLicenseId($profileId) {
        parent::parameterQuery(self::LICENSE_ID, $profileId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllLicensesByProfileId($licensesId, $profileId) {
        $mySql         = new MySQL();
        $whereLicenses = array_map(function($licenseId) use (&$whereLicenses) {
            $param = self::LICENSE_ID . $licenseId;
            parent::parameterQuery($param, $licenseId, \PDO::PARAM_INT, self::LICENSE_ID);
            
            return self::LICENSE_ID . " = :$param";
        }, $licensesId);
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        $implode = implode(' OR ', $whereLicenses);
        $query   = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::PROFILE_ID, $implode);
        $result  = $mySql->delete($query, $this->prepare);
        parent::closeConnection($mySql);
        
        return $result;
    }
    
    public function deleteAllProfilesByLicenseId($profilesId, $licenseId) {
        $mySql           = new MySQL();
        $whereProfilesId = array_map(function($profileId) use (&$whereProfilesId) {
            $param = self::PROFILE_ID . $profileId;
            parent::parameterQuery($param, $profileId, \PDO::PARAM_INT, self::PROFILE_ID);
            
            return self::PROFILE_ID . " = :$param";
        }, $profilesId);
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        $implode = implode(' OR ', $whereProfilesId);
        $query   = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::LICENSE_ID, $implode);
        $result  = $mySql->delete($query, $this->prepare);
        parent::closeConnection($mySql);
        
        return $result;
    }
    
    /**
     * @param ProfileLicense $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
        parent::parameterQuery(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $userLicense = new ProfileLicense();
        $userLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        $userLicense->setProfileId(Arrays::get($result, self::PROFILE_ID));
        
        return $userLicense;
    }
}
