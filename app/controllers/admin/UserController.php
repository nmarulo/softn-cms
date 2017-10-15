<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Gravatar;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(Constants::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $usersManager = new UsersManager();
                $user         = Arrays::get($form, 'user');
                
                if ($usersManager->create($user)) {
                    Messages::addSuccess(__('Usuario creado correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/user');
                }
            }
            
            Messages::addDanger(__('Error al publicar el usuario.'));
        }
        
        $this->sendViewProfiles();
        $user     = new User();
        $gravatar = $this->getGravatar();
        //En el panel de administración el tamaño sera 128px
        $gravatar->setSize(128);
        $user->setUserUrlImage($gravatar->get());
        ViewController::sendViewData('isUpdate', FALSE);
        ViewController::sendViewData('user', $user);
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
        
        $gravatar = $this->getGravatar();
        $user     = new User();
        $user->setId(Arrays::get($inputs, UsersManager::COLUMN_ID));
        $user->setUserEmail(Arrays::get($inputs, UsersManager::USER_EMAIL));
        $user->setUserLogin(Arrays::get($inputs, UsersManager::USER_LOGIN));
        $user->setUserName(Arrays::get($inputs, UsersManager::USER_NAME));
        $user->setUserRegistered(NULL);
        $user->setUserUrl(Arrays::get($inputs, UsersManager::USER_URL));
        $user->setUserPassword($pass);
        $user->setUserPostCount(NULL);
        $user->setProfileId(Arrays::get($inputs, UsersManager::PROFILE_ID));
        $gravatar->setEmail($user->getUserEmail());
        $user->setUserUrlImage($gravatar->get());
        
        if (Form::submit(Constants::FORM_CREATE)) {
            $user->setUserRegistered(Util::dateNow());
            $user->setUserPostCount(0);
        }
        
        return [
            'user' => $user,
        ];
    }
    
    protected function filterInputs() {
        $isCreate = Form::submit(Constants::FORM_CREATE);
        
        Form::setInput([
            InputIntegerBuilder::init(UsersManager::COLUMN_ID)
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
            InputIntegerBuilder::init(UsersManager::PROFILE_ID)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function getGravatar() {
        $optionsManager = new OptionsManager();
        $gravatarOption = $optionsManager->searchByName(OptionConstants::GRAVATAR);
        
        if (empty($gravatarOption->getOptionValue())) {
            $gravatar = new Gravatar();
        } else {
            $gravatar = unserialize($gravatarOption->getOptionValue());
        }
        
        return $gravatar;
    }
    
    private function sendViewProfiles() {
        $profilesManager = new ProfilesManager();
        ViewController::sendViewData('profiles', $profilesManager->searchAll());
    }
    
    public function update($id) {
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            Messages::addDanger(__('El usuario no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/user');
        } elseif (Form::submit(Constants::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos del usuario.'));
            } else {
                $userForm = Arrays::get($form, 'user');
                
                if ($usersManager->updateByColumnId($userForm)) {
                    $user = $usersManager->searchById($id);
                    Messages::addSuccess(__('Usuario actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el usuario.'));
                }
            }
        }
        
        $this->sendViewProfiles();
        $gravatar = $this->getGravatar();
        //En el panel de administración el tamaño sera 128px
        $gravatar->setSize(128);
        $gravatar->setEmail($user->getUserEmail());
        $user->setUserUrlImage($gravatar->get());
        ViewController::sendViewData('isUpdate', TRUE);
        ViewController::sendViewData('selectedProfileId', $user->getProfileId());
        ViewController::sendViewData('user', $user);
        ViewController::sendViewData('title', __('Actualizar usuario'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        if ($id == LoginManager::getSession()) {
            Messages::addDanger(__('No puedes eliminar este usuario.'));
        } else {
            $usersManager = new UsersManager();
            $result       = $usersManager->deleteById($id);
            $rowCount     = $usersManager->getRowCount();
            
            if ($result === FALSE) {
                Messages::addDanger(__('No se puede borrar un usuario con entradas publicadas.'));
            } elseif ($rowCount > 0) {
                Messages::addSuccess(__('Usuario borrado correctamente.'));
            } else {
                Messages::addDanger(__('Error al borrar el usuario.'));
            }
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $usersManager = new UsersManager();
        $count        = $usersManager->count();
        $limit        = parent::pagination($count);
        
        ViewController::sendViewData('users', $usersManager->searchAll($limit));
    }
}
