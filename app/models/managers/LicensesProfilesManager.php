<?php
/**
 * LicensesProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\LicenseProfile;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\MySQL;

/**
 * Class LicensesProfilesManager
 * @author Nicolás Marulanda P.
 */
class LicensesProfilesManager extends CRUDManagerAbstract {
    
    const TABLE      = 'licenses_profiles';
    
    const LICENSE_ID = 'license_id';
    
    const PROFILE_ID = 'profile_id';
    
    public function deleteAllByProfileId($profileId) {
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function deleteAllByLicenseId($licenseId) {
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        return parent::deleteBy();
    }
    
    public function searchAllByProfileId($profileId) {
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::PROFILE_ID);
    }
    
    public function searchAllByLicenseId($licenseId) {
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        
        return parent::searchAllBy(self::LICENSE_ID);
    }
    
    public function deleteAllLicensesByProfileId($licensesId, $profileId) {
        $mySql           = new MySQL();
        $queryLicensesId = '';
        parent::parameterQuery(self::PROFILE_ID, $profileId, \PDO::PARAM_INT);
        array_walk($licensesId, function($licenseId) use (&$queryLicensesId) {
            $param = self::LICENSE_ID . $licenseId;
            parent::parameterQuery($param, $licenseId, \PDO::PARAM_INT, self::LICENSE_ID);
            $queryLicensesId .= empty($queryLicensesId) ? '' : ' OR ';
            $queryLicensesId .= self::LICENSE_ID . " = :$param";
        });
        
        $query  = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::PROFILE_ID, $queryLicensesId);
        $result = $mySql->delete($query, $this->prepare);
        parent::closeConnection($mySql);
        
        return $result;
    }
    public function deleteAllProfilesByLicenseId($profilesId, $licenseId) {
        $mySql           = new MySQL();
        $queryProfilesId = '';
        parent::parameterQuery(self::LICENSE_ID, $licenseId, \PDO::PARAM_INT);
        array_walk($profilesId, function($profileId) use (&$queryProfilesId) {
            $param = self::PROFILE_ID . $profileId;
            parent::parameterQuery($param, $profileId, \PDO::PARAM_INT, self::PROFILE_ID);
            $queryProfilesId .= empty($queryProfilesId) ? '' : ' OR ';
            $queryProfilesId .= self::PROFILE_ID . " = :$param";
        });
        
        $query  = sprintf('DELETE FROM %1$s WHERE %2$s = :%2$s AND (%3$s)', $this->getTableWithPrefix(), self::LICENSE_ID, $queryProfilesId);
        $result = $mySql->delete($query, $this->prepare);
        parent::closeConnection($mySql);
        
        return $result;
    }
    
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
