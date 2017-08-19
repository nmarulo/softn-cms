<?php
/**
 * UserProfile.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class UserProfile
 * @author Nicolás Marulanda P.
 */
class UserProfile {
    
    /** @var int */
    private $userId;
    
    /** @var int */
    private $profileId;
    
    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }
    
    /**
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
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
