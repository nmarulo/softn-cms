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
class License extends TableAbstract {
    
    /** @var string */
    private $licenseName;
    
    /** @var string */
    private $licenseDescription;
    
    /**
     * @return string
     */
    public function getLicenseName() {
        return $this->licenseName;
    }
    
    /**
     * @param string $licenseName
     */
    public function setLicenseName($licenseName) {
        $this->licenseName = $licenseName;
    }
    
    /**
     * @return string
     */
    public function getLicenseDescription() {
        return $this->licenseDescription;
    }
    
    /**
     * @param string $licenseDescription
     */
    public function setLicenseDescription($licenseDescription) {
        $this->licenseDescription = $licenseDescription;
    }
    
}
