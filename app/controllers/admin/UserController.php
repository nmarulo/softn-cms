<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\managers\UsersProfilesManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\models\tables\UserProfile;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $usersManager = new UsersManager();
                $user         = Arrays::get($form, 'user');
                
                if ($usersManager->create($user)) {
                    $profiles = Arrays::get($form, 'profiles');
                    $this->createOrDeleteProfiles($profiles, $usersManager->getLastInsertId());
                    Messages::addSuccess(__('Usuario creado correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/user');
                }
            }
            
            Messages::addDanger(__('Error al publicar el usuario.'));
        }
        
        $this->sendViewProfiles();
        ViewController::sendViewData('user', new User());
        ViewController::sendViewData('title', __('Publicar nuevo usuario'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $pass  = Arrays::get($inputs, UsersManager::USER_PASSWORD);
        $passR = Arrays::get($inputs, UsersManager::USER_PASSWORD_REWRITE);
        
        if (empty($pass) || empty($passR)) {
            $pass = NULL;
        } else {
            if ($pass != $passR) {
                return FALSE;
            }
            
            $pass = Util::encrypt($pass, LOGGED_KEY);
        }
        
        $user     = new User();
        $profiles = Arrays::get($inputs, UsersProfilesManager::PROFILE_ID);
        $user->setId(Arrays::get($inputs, UsersManager::ID));
        $user->setUserEmail(Arrays::get($inputs, UsersManager::USER_EMAIL));
        $user->setUserLogin(Arrays::get($inputs, UsersManager::USER_LOGIN));
        $user->setUserName(Arrays::get($inputs, UsersManager::USER_NAME));
        $user->setUserRegistered(NULL);
        $user->setUserUrl(Arrays::get($inputs, UsersManager::USER_URL));
        $user->setUserPassword($pass);
        $user->setUserPostCount(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $user->setUserRegistered(Util::dateNow());
            $user->setUserPostCount(0);
        }
        
        return [
            'user'     => $user,
            'profiles' => $profiles,
        ];
    }
    
    protected function filterInputs() {
        $isCreate = Form::submit(CRUDManagerAbstract::FORM_CREATE);
        
        Form::setInput([
            InputIntegerBuilder::init(UsersManager::ID)
                               ->build(),
            InputEmailBuilder::init(UsersManager::USER_EMAIL)
                             ->build(),
            InputAlphanumericBuilder::init(UsersManager::USER_LOGIN)
                                    ->setAccents(FALSE)
                                    ->setWithoutSpace(TRUE)
                                    ->setReplaceSpace('')
                                    ->build(),
            InputAlphanumericBuilder::init(UsersManager::USER_NAME)
                                    ->build(),
            InputUrlBuilder::init(UsersManager::USER_URL)
                           ->setRequire(FALSE)
                           ->build(),
            InputAlphanumericBuilder::init(UsersManager::USER_PASSWORD)
                                    ->setRequire($isCreate)
                                    ->build(),
            InputAlphanumericBuilder::init(UsersManager::USER_PASSWORD_REWRITE)
                                    ->setRequire($isCreate)
                                    ->build(),
            InputListIntegerBuilder::init(UsersProfilesManager::PROFILE_ID)
                                   ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function sendViewProfiles() {
        $profilesManager = new ProfilesManager();
        ViewController::sendViewData('profiles', $profilesManager->read());
    }
    
    public function update($id) {
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            Messages::addDanger(__('El usuario no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/user');
        } elseif (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos del usuario.'));
            } else {
                $user = Arrays::get($form, 'user');
                
                if ($usersManager->update($user)) {
                    $user     = $usersManager->searchById($id);
                    $profiles = Arrays::get($form, 'profiles');
                    $this->createOrDeleteProfiles($profiles, $id);
                    Messages::addSuccess(__('Usuario actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el usuario.'));
                }
            }
        }
        
        $this->sendViewProfiles();
        ViewController::sendViewData('selectedProfilesId', $this->getProfilesIdByUserId($id));
        ViewController::sendViewData('user', $user);
        ViewController::sendViewData('title', __('Actualizar usuario'));
        ViewController::view('form');
    }
    
    private function createOrDeleteProfiles($profilesId, $userId) {
        $usersProfilesManager = new UsersProfilesManager();
        
        if (empty($profilesId)) {
            if ($usersProfilesManager->deleteAllByUserId($userId) === FALSE) {
                Messages::addDanger(__('Error al borrar los perfiles.'));
            }
        } else {
            $selectedProfilesId = $this->getProfilesIdByUserId($userId);
            $numError           = 0;
            //Obtengo los identificadores de los nuevos perfiles.
            $newSelectedProfilesId = array_filter($profilesId, function($profileId) use ($selectedProfilesId) {
                return !Arrays::valueExists($selectedProfilesId, $profileId);
            });
            $newUsersProfiles      = array_map(function($profileId) use ($userId) {
                $userProfile = new UserProfile();
                $userProfile->setProfileId($profileId);
                $userProfile->setUserId($userId);
                
                return $userProfile;
            }, $newSelectedProfilesId);
            
            //Obtengo los identificadores de los perfiles que no se han seleccionado.
            $profilesIdNotSelected = array_filter($selectedProfilesId, function($selectedProfileId) use ($profilesId) {
                return !Arrays::valueExists($profilesId, $selectedProfileId);
            });
            
            if (!empty($profilesIdNotSelected) && $usersProfilesManager->deleteAllProfilesByUserId($profilesIdNotSelected, $userId) === FALSE) {
                Messages::addDanger(__('Error al borrar los perfiles.'));
            }
            
            array_walk($newUsersProfiles, function(UserProfile $userProfile) use (&$numError, $usersProfilesManager) {
                if (!$usersProfilesManager->create($userProfile)) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los perfiles.'));
            }
        }
    }
    
    private function getProfilesIdByUserId($userId) {
        $usersProfilesManager = new UsersProfilesManager();
        $userProfiles         = $usersProfilesManager->searchAllByUserId($userId);
        
        return array_map(function(UserProfile $userProfile) {
            return $userProfile->getProfileId();
        }, $userProfiles);
    }
    
    public function delete($id) {
        if ($id == LoginManager::getSession()) {
            Messages::addDanger(__('No puedes eliminar este usuario.'));
        } else {
            $usersManager = new UsersManager();
            $result       = $usersManager->delete($id);
            
            if ($result === FALSE) {
                Messages::addDanger(__('No se puede borrar un usuario con entradas publicadas.'));
            } elseif ($result == 0) {
                Messages::addDanger(__('Error al borrar el usuario.'));
            } elseif ($result > 0) {
                Messages::addSuccess(__('Usuario borrado correctamente.'));
            }
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters      = [];
        $usersManager = new UsersManager();
        $count        = $usersManager->count();
        $pagination   = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('users', $usersManager->read($filters));
    }
}
