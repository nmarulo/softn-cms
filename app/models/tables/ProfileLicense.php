<?php
/**
 * LicenseProfile.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class LicenseProfile
 * @author Nicolás Marulanda P.
 */
class ProfileLicense {
    
    /** @var int */
    private $licenseId;
    
    /** @var int */
    private $profileId;
    
    /**
     * @return int
     */
    public function getLicenseId() {
        return $this->licenseId;
    }
    
    /**
     * @param int $licenseId
     */
    public function setLicenseId($licenseId) {
        $this->licenseId = $licenseId;
    }
    
    /**
     * @return int
     */
    public function getProfileId() {
        return $this->profileId;
    }
    
    /**
     * @param int $profileId
     */
    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }
    
}
