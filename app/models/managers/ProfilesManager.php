<?php
/**
 * ProfilesManager.php
 */

namespace SoftnCMS\models\managers;

use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\util\Arrays;

/**
 * Class ProfilesManager
 * @author Nicolás Marulanda P.
 */
class ProfilesManager extends CRUDManagerAbstract {
    
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
        parent::parameterQuery(self::PROFILE_NAME, $name, \PDO::PARAM_STR);
        $result = parent::searchBy(self::PROFILE_NAME);
        
        //Si el "id" es el mismo, estamos actualizando.
        return $result !== FALSE && $result->getId() != $id;
    }
    
    /**
     * @param Profile $object
     */
    protected function addParameterQuery($object) {
        parent::parameterQuery(self::PROFILE_DESCRIPTION, $object->getProfileDescription(), \PDO::PARAM_STR);
        parent::parameterQuery(self::PROFILE_NAME, $object->getProfileName(), \PDO::PARAM_STR);
    }
    
    protected function getTable() {
        return self::TABLE;
    }
    
    protected function buildObjectTable($result) {
        parent::buildObjectTable($result);
        $profile = new Profile();
        $profile->setId(Arrays::get($result, self::ID));
        $profile->setProfileName(Arrays::get($result, self::PROFILE_NAME));
        $profile->setProfileDescription(Arrays::get($result, self::PROFILE_DESCRIPTION));
        
        return $profile;
    }
}
