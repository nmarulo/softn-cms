<?php
/**
 * LicensesProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class LicensesProfilesManager
 * @author Nicolás Marulanda P.
 */
class ProfilesLicensesManager extends ManagerAbstract {
    
    const TABLE      = 'profiles_licenses';
    
    const LICENSE_ID = 'license_id';
    
    const PROFILE_ID = 'profile_id';
    
    public function searchAllByUserId($userId) {
        parent::addPrepareStatement(UsersManager::COLUMN_ID, $userId, \PDO::PARAM_INT);
        $tableUsers = parent::getTableWithPrefix(UsersManager::TABLE);
        $query      = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s.%4$s FROM %3$s WHERE %5$s = :%5$s)';
        $query      = sprintf($query, $this->getTableWithPrefix(), self::PROFILE_ID, $tableUsers, UsersManager::PROFILE_ID, UsersManager::COLUMN_ID);
        
        return parent::search($query);
    }
    
    public function searchAllByProfileId($profileId) {
        return parent::searchAllByColumn($profileId, self::PROFILE_ID, \PDO::PARAM_INT);
    }
    
    public function searchAllByLicenseId($licenseId) {
        return parent::searchAllByColumn($licenseId, self::LICENSE_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllByProfileId($profileId) {
        return parent::deleteByColumn($profileId, self::PROFILE_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllByLicenseId($profileId) {
        return parent::deleteByColumn($profileId, self::LICENSE_ID, \PDO::PARAM_INT);
    }
    
    public function deleteAllLicensesByProfileId($licensesId, $profileId) {
        $whereLicenses = array_map(function($licenseId) use (&$whereLicenses) {
            $param = self::LICENSE_ID . $licenseId;
            parent::addPrepareStatement($param, $licenseId, \PDO::PARAM_INT, self::LICENSE_ID);
            
            return self::LICENSE_ID . " = :$param";
        }, $licensesId);
        parent::addPrepareStatement(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        $implode = implode(' OR ', $whereLicenses);
        $query   = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::PROFILE_ID, $implode);
        
        return parent::delete($query);
    }
    
    public function deleteAllProfilesByLicenseId($profilesId, $licenseId) {
        $whereProfilesId = array_map(function($profileId) use (&$whereProfilesId) {
            $param = self::PROFILE_ID . $profileId;
            parent::addPrepareStatement($param, $profileId, \PDO::PARAM_INT, self::PROFILE_ID);
            
            return self::PROFILE_ID . " = :$param";
        }, $profilesId);
        parent::addPrepareStatement(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        $implode = implode(' OR ', $whereProfilesId);
        $query   = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::LICENSE_ID, $implode);
        
        return parent::delete($query);
    }
    
    /**
     * @param ProfileLicense $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::PROFILE_ID, $object->getProfileId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::LICENSE_ID, $object->getLicenseId(), \PDO::PARAM_INT);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $userLicense = new ProfileLicense();
        $userLicense->setLicenseId(Arrays::get($result, self::LICENSE_ID));
        $userLicense->setProfileId(Arrays::get($result, self::PROFILE_ID));
        
        return $userLicense;
    }
}
