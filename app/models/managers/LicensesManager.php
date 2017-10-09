<?php
/**
 * LicensesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\License;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class LicensesManager
 * @author Nicolás Marulanda P.
 */
class LicensesManager extends ManagerAbstract {
    
    const TABLE               = 'licenses';
    
    const LICENSE_NAME        = 'license_name';
    
    const LICENSE_DESCRIPTION = 'license_description';
    
    public function searchAllWithoutConfigured($limit = '') {
        $tableOptionLicense = parent::getTableWithPrefix(OptionsLicensesManager::TABLE);
        $query              = 'SELECT * FROM %1$s WHERE %2$s NOT IN (SELECT %3$s FROM %4$s)';
        $query              = sprintf($query, $this->getTableWithPrefix(), self::COLUMN_ID, OptionsLicensesManager::LICENSE_ID, $tableOptionLicense);
        $query              .= empty($limit) ? '' : " LIMIT $limit";
        
        return parent::search($query);
    }
    
    public function searchAllConfigured($limit = '') {
        $tableOptionLicense = parent::getTableWithPrefix(OptionsLicensesManager::TABLE);
        $query              = 'SELECT * FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s) ORDER BY %2$s DESC';
        $query              = sprintf($query, $this->getTableWithPrefix(), self::COLUMN_ID, OptionsLicensesManager::LICENSE_ID, $tableOptionLicense);
        $query              .= empty($limit) ? '' : " LIMIT $limit";
        
        return parent::search($query);
    }
    
    public function configuredCount() {
        $tableOptionLicense = parent::getTableWithPrefix(OptionsLicensesManager::TABLE);
        $query              = 'SELECT COUNT(*) AS COUNT FROM %1$s WHERE %2$s IN (SELECT %3$s FROM %4$s)';
        $query              = sprintf($query, $this->getTableWithPrefix(), self::COLUMN_ID, OptionsLicensesManager::LICENSE_ID, $tableOptionLicense);
        $db                 = parent::getDB();
        $result             = Arrays::findFirst($db->select($query));
        
        return empty($result) ? 0 : $result;
    }
    
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
        $result = parent::searchAllByColumn($name, self::LICENSE_NAME, \PDO::PARAM_STR, ['ORDER BY ' . self::COLUMN_ID . ' DESC']);
        $result = Arrays::findFirst($result);
        
        //Si el "id" es el mismo, estamos actualizando.
        return !empty($result) && $result->getId() != $id;
    }
    
    /**
     * @param License $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::LICENSE_NAME, $object->getLicenseName(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::LICENSE_DESCRIPTION, $object->getLicenseDescription(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $license = new License();
        $license->setId(Arrays::get($result, self::COLUMN_ID));
        $license->setLicenseDescription(Arrays::get($result, self::LICENSE_DESCRIPTION));
        $license->setLicenseName(Arrays::get($result, self::LICENSE_NAME));
        
        return $license;
    }
    
}
