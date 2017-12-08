<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class UserController
 * @author Nicolás Marulanda P.
 */
class UserController extends ControllerAbstract {
    
    public function index() {
        $usersManager = new UsersManager($this->getConnectionDB());
        $count        = $usersManager->count();
        
        $this->sendDataView([
            'users' => $usersManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        $usersManager = new UsersManager($this->getConnectionDB());
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $user = $this->getForm('user');
                
                if ($usersManager->create($user)) {
                    Messages::addSuccess(__('Usuario creado correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al publicar el usuario.'));
        }
        
        $this->sendViewProfiles();
        $user     = new User();
        $gravatar = $usersManager->getGravatar(NULL);
        //En el panel de administración el tamaño sera 128px
        $gravatar->setSize(128);
        $user->setUserUrlImage($gravatar->get());
        $this->sendDataView([
            'isUpdate' => FALSE,
            'user'     => $user,
            'title'    => __('Publicar nuevo usuario'),
        ]);
        $this->view('form');
    }
    
    private function sendViewProfiles() {
        $profilesManager = new ProfilesManager($this->getConnectionDB());
        $this->sendDataView(['profiles' => $profilesManager->searchAll()]);
    }
    
    public function update($id) {
        $usersManager = new UsersManager($this->getConnectionDB());
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            Messages::addDanger(__('El usuario no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $userForm = $this->getForm('user');
                
                if ($usersManager->updateByColumnId($userForm)) {
                    $user = $usersManager->searchById($id);
                    Messages::addSuccess(__('Usuario actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el usuario.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos del usuario.'));
            }
        }
        
        $this->sendViewProfiles();
        $gravatar = $usersManager->getGravatar($user->getUserEmail());
        //En el panel de administración el tamaño sera 128px
        $gravatar->setSize(128);
        $user->setUserUrlImage($gravatar->get());
        $this->sendDataView([
            'isUpdate'          => TRUE,
            'selectedProfileId' => $user->getProfileId(),
            'user'              => $user,
            'title'             => __('Actualizar usuario'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            if ($id == LoginManager::getUserId()) {
                Messages::addDanger(__('No puedes eliminar este usuario.'), TRUE);
            } else {
                $usersManager = new UsersManager($this->getConnectionDB());
                $result       = $usersManager->deleteById($id);
                $rowCount     = $usersManager->getRowCount();
                
                if ($result === FALSE) {
                    Messages::addDanger(__('No se puede borrar un usuario con entradas publicadas.'), TRUE);
                } elseif ($rowCount === 0) {
                    Messages::addDanger(__('Error al borrar el usuario.'), TRUE);
                } else {
                    Messages::addSuccess(__('Usuario borrado correctamente.'), TRUE);
                }
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $pass  = $this->getInput(UsersManager::USER_PASSWORD);
        $passR = $this->getInput(UsersManager::USER_PASSWORD_REWRITE);
        
        if (empty($pass) || empty($passR)) {
            $pass = NULL;
        } else {
            if ($pass != $passR) {
                return FALSE;
            }
            
            $pass = Util::encrypt($pass, LOGGED_KEY);
        }
        
        $usersManager = new UsersManager($this->getConnectionDB());
        $user         = new User();
        $user->setId($this->getInput(UsersManager::COLUMN_ID));
        $user->setUserEmail($this->getInput(UsersManager::USER_EMAIL));
        $user->setUserLogin($this->getInput(UsersManager::USER_LOGIN));
        $user->setUserName($this->getInput(UsersManager::USER_NAME));
        $user->setUserRegistered(NULL);
        $user->setUserUrl($this->getInput(UsersManager::USER_URL));
        $user->setUserPassword($pass);
        $user->setUserPostCount(NULL);
        $user->setProfileId($this->getInput(UsersManager::PROFILE_ID));
        $gravatar = $usersManager->getGravatar($user->getUserEmail());
        $user->setUserUrlImage($gravatar->get());
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            $user->setUserRegistered(Util::dateNow());
            $user->setUserPostCount(0);
        }
        
        return [
            'user' => $user,
        ];
    }
    
    protected function formInputsBuilders() {
        $isCreate = $this->checkSubmit(Constants::FORM_CREATE);
        
        return [
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
        ];
    }
    
}
