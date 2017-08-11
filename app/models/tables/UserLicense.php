<?php
/**
 * License.php
 */

namespace SoftnCMS\models\tables;

use SoftnCMS\models\TableAbstract;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
class UserLicense extends TableAbstract {
    
    /** @var string */
    private $userLicenseName;
    
    /** @var string */
    private $userLicenseDescription;
    
    /**
     * @return string
     */
    public function getUserLicenseName() {
        return $this->userLicenseName;
    }
    
    /**
     * @param string $userLicenseName
     */
    public function setUserLicenseName($userLicenseName) {
        $this->userLicenseName = $userLicenseName;
    }
    
    /**
     * @return string
     */
    public function getUserLicenseDescription() {
        return $this->userLicenseDescription;
    }
    
    /**
     * @param string $userLicenseDescription
     */
    public function setUserLicenseDescription($userLicenseDescription) {
        $this->userLicenseDescription = $userLicenseDescription;
    }
    
}
