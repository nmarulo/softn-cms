<?php
/**
 * LicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\License;
use SoftnCMS\util\Arrays;

/**
 * Class LicensesManager
 * @author Nicolás Marulanda P.
 */
class LicensesManager extends CRUDManagerAbstract {
    
    const TABLE               = 'licenses';
    
    const LICENSE_NAME        = 'license_name';
    
    const LICENSE_DESCRIPTION = 'license_description';
    
    public function create($object) {
        $object = $this->checkName($object);
        
        return parent::create($object);
    }
    
    /**
     * @param License $license
     *
     * @return License
     */
    private function checkName($license) {
        $name    = $license->getLicenseName();
        $id      = $license->getId();
        $newName = $name;
        $num     = 0;
        
        while ($this->nameExists($newName, $id)) {
            $newName = $name . ++$num;
        }
        
        $license->setLicenseName($newName);
        
        return $license;
    }
    
    private function nameExists($name, $id) {
        parent::parameterQuery(self::LICENSE_NAME, $name, \PDO::PARAM_STR);
        $result = parent::searchBy(self::LICENSE_NAME);
        
        //Si el "id" es el mismo, estamos actualizando.
        return $result !== FALSE && $result->getId() != $id;
    }
    
    /**
     * @param License $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::LICENSE_NAME, $object->getLicenseName(), \PDO::PARAM_STR);
        parent::parameterQuery(self::LICENSE_DESCRIPTION, $object->getLicenseDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $license = new License();
        $license->setId(Arrays::get($result, self::ID));
        $license->setLicenseDescription(Arrays::get($result, self::LICENSE_DESCRIPTION));
        $license->setLicenseName(Arrays::get($result, self::LICENSE_NAME));
        
        return $license;
    }
    
}
