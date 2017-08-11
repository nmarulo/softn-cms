<?php
/**
 * LicensesProfiles.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class LicensesProfiles
 * @author Nicolás Marulanda P.
 */
class LicensesProfiles {
    
    /** @var int */
    private $userLicenseId;
    
    /** @var int */
    private $userProfileId;
    
    /**
     * @return int
     */
    public function getUserLicenseId() {
        return $this->userLicenseId;
    }
    
    /**
     * @param int $userLicenseId
     */
    public function setUserLicenseId($userLicenseId) {
        $this->userLicenseId = $userLicenseId;
    }
    
    /**
     * @return int
     */
    public function getUserProfileId() {
        return $this->userProfileId;
    }
    
    /**
     * @param int $userProfileId
     */
    public function setUserProfileId($userProfileId) {
        $this->userProfileId = $userProfileId;
    }
    
}
