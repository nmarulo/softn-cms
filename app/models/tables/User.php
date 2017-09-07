<?php
/**
 * User.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class User
 * @author Nicolás Marulanda P.
 */
class User extends TableAbstract {
    
    /** @var string */
    private $userLogin;
    
    /** @var string */
    private $userName;
    
    /** @var string */
    private $userEmail;
    
    /** @var string */
    private $userPassword;
    
    /** @var string */
    private $userRegistered;
    
    /** @var string */
    private $userUrl;
    
    /** @var int */
    private $userPostCount;
    
    /** @var int */
    private $profileId;
    
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
    
    /**
     * @return int
     */
    public function getUserPostCount() {
        return $this->userPostCount;
    }
    
    /**
     * @param int $userPostCount
     */
    public function setUserPostCount($userPostCount) {
        $this->userPostCount = $userPostCount;
    }
    
    /**
     * @return string
     */
    public function getUserLogin() {
        return $this->userLogin;
    }
    
    /**
     * @param string $userLogin
     */
    public function setUserLogin($userLogin) {
        $this->userLogin = $userLogin;
    }
    
    /**
     * @return string
     */
    public function getUserName() {
        return $this->userName;
    }
    
    /**
     * @param string $userName
     */
    public function setUserName($userName) {
        $this->userName = $userName;
    }
    
    /**
     * @return string
     */
    public function getUserEmail() {
        return $this->userEmail;
    }
    
    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }
    
    /**
     * @return string
     */
    public function getUserPassword() {
        return $this->userPassword;
    }
    
    /**
     * @param string $userPassword
     */
    public function setUserPassword($userPassword) {
        $this->userPassword = $userPassword;
    }
    
    /**
     * @return string
     */
    public function getUserRegistered() {
        return $this->userRegistered;
    }
    
    /**
     * @param string $userRegistered
     */
    public function setUserRegistered($userRegistered) {
        $this->userRegistered = $userRegistered;
    }
    
    /**
     * @return string
     */
    public function getUserUrl() {
        return $this->userUrl;
    }
    
    /**
     * @param string $userUrl
     */
    public function setUserUrl($userUrl) {
        $this->userUrl = $userUrl;
    }
    
}
