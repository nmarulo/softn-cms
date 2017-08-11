<?php
/**
 * UsersProfiles.php
 */

namespace SoftnCMS\models\tables;

/**
 * Class UsersProfiles
 * @author Nicolás Marulanda P.
 */
class UsersProfiles {
    
    /** @var int */
    private $userId;
    
    /** @var int */
    private $userProfileId;
    
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
