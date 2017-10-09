<?php
/**
 * ProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\tables\Profile;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\database\ManagerAbstract;

/**
 * Class ProfilesManager
 * @author Nicolás Marulanda P.
 */
class ProfilesManager extends ManagerAbstract {
    
    const TABLE               = 'profiles';
    
    const PROFILE_NAME        = 'profile_name';
    
    const PROFILE_DESCRIPTION = 'profile_description';
    
    public function create($object) {
        $object = $this->checkName($object);
        
        return parent::create($object);
    }
    
    /**
     * @param Profile $profile
     *
     * @return Profile
     */
    private function checkName($profile) {
        $name    = $profile->getProfileName();
        $id      = $profile->getId();
        $newName = $name;
        $num     = 0;
        
        while ($this->nameExists($newName, $id)) {
            $newName = $name . ++$num;
        }
        
        $profile->setProfileName($newName);
        
        return $profile;
    }
    
    private function nameExists($name, $id) {
        $result = parent::searchAllByColumn($name, self::PROFILE_NAME, \PDO::PARAM_STR);
        $result = Arrays::findFirst($result);
        
        //Si el "id" es el mismo, estamos actualizando.
        return !empty($result) && $result->getId() != $id;
    }
    
    /**
     * @param Profile $object
     */
    protected function prepareStatement($object) {
        parent::addPrepareStatement(self::COLUMN_ID, $object->getId(), \PDO::PARAM_INT);
        parent::addPrepareStatement(self::PROFILE_DESCRIPTION, $object->getProfileDescription(), \PDO::PARAM_STR);
        parent::addPrepareStatement(self::PROFILE_NAME, $object->getProfileName(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObject($result) {
        $profile = new Profile();
        $profile->setId(Arrays::get($result, self::COLUMN_ID));
        $profile->setProfileName(Arrays::get($result, self::PROFILE_NAME));
        $profile->setProfileDescription(Arrays::get($result, self::PROFILE_DESCRIPTION));
        
        return $profile;
    }
}
