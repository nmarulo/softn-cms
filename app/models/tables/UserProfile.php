<?php
/**
 * Profile.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class Profile
 * @author Nicolás Marulanda P.
 */
class UserProfile extends TableAbstract {
    
    /** @var string */
    private $userProfileName;
    
    /** @var string */
    private $userProfileDescription;
    
    /**
     * @return string
     */
    public function getUserProfileName() {
        return $this->userProfileName;
    }
    
    /**
     * @param string $userProfileName
     */
    public function setUserProfileName($userProfileName) {
        $this->userProfileName = $userProfileName;
    }
    
    /**
     * @return string
     */
    public function getUserProfileDescription() {
        return $this->userProfileDescription;
    }
    
    /**
     * @param string $userProfileDescription
     */
    public function setUserProfileDescription($userProfileDescription) {
        $this->userProfileDescription = $userProfileDescription;
    }
    
}
