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
class Profile extends TableAbstract {
    
    /** @var string */
    private $profileName;
    
    /** @var string */
    private $profileDescription;
    
    /**
     * @return string
     */
    public function getProfileName() {
        return $this->profileName;
    }
    
    /**
     * @param string $profileName
     */
    public function setProfileName($profileName) {
        $this->profileName = $profileName;
    }
    
    /**
     * @return string
     */
    public function getProfileDescription() {
        return $this->profileDescription;
    }
    
    /**
     * @param string $profileDescription
     */
    public function setProfileDescription($profileDescription) {
        $this->profileDescription = $profileDescription;
    }
    
}
