<?php
/**
 * UserController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
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
        $showForm = TRUE;
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form        = $this->form();
            $messages    = 'Error al publicar el usuario.';
            $typeMessage = Messages::TYPE_DANGER;
            
            if (!empty($form)) {
                $usersManager = new UsersManager();
                $user         = Arrays::get($form, 'user');
                
                if ($usersManager->create($user)) {
                    $showForm    = FALSE;
                    $messages    = 'Usuario creado correctamente.';
                    $typeMessage = Messages::TYPE_SUCCESS;
                    Messages::addMessage($messages, $typeMessage);
                    $this->index();
                }
            }
            
            Messages::addMessage($messages, $typeMessage);
        }
        
        if ($showForm) {
            ViewController::sendViewData('user', new User());
            ViewController::sendViewData('title', 'Publicar nuevo usuario');
            ViewController::view('form');
        }
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
        
        $user = new User();
        $user->setId(Arrays::get($inputs, UsersManager::ID));
        $user->setUserEmail(Arrays::get($inputs, UsersManager::USER_EMAIL));
        $user->setUserLogin(Arrays::get($inputs, UsersManager::USER_LOGIN));
        $user->setUserName(Arrays::get($inputs, UsersManager::USER_NAME));
        $user->setUserRegistered(NULL);
        $user->setUserRol(Arrays::get($inputs, UsersManager::USER_ROL));
        $user->setUserUrl(Arrays::get($inputs, UsersManager::USER_URL));
        $user->setUserPassword($pass);
        $user->setUserPostCount(NULL);
        
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $user->setUserRegistered(Util::dateNow());
            $user->setUserPostCount(0);
        }
        
        return ['user' => $user];
    }
    
    protected function filterInputs() {
        $isCreate = Form::submit(CRUDManagerAbstract::FORM_CREATE);
        
        Form::setINPUT([
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
            InputIntegerBuilder::init(UsersManager::USER_ROL)
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
        ]);
        
        return Form::inputFilter();
    }
    
    public function index() {
        $this->read();
        ViewController::view('index');
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
    
    public function update($id) {
        $typeMessage  = Messages::TYPE_DANGER;
        $messages     = 'El usuario no existe.';
        $usersManager = new UsersManager();
        $user         = $usersManager->searchById($id);
        
        if (empty($user)) {
            Messages::addMessage($messages, $typeMessage);
            $this->index();
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $messages = 'Error al actualizar el usuario.';
                $form     = $this->form();
                
                if (!empty($form)) {
                    $user = Arrays::get($form, 'user');
                    
                    if ($usersManager->update($user)) {
                        $messages    = 'Usuario actualizado correctamente.';
                        $typeMessage = Messages::TYPE_SUCCESS;
                    }
                }
                
                Messages::addMessage($messages, $typeMessage);
            }
            
            ViewController::sendViewData('user', $user);
            ViewController::sendViewData('title', 'Actualizar usuario');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $messages    = 'Error al borrar el usuario.';
        $typeMessage = Messages::TYPE_DANGER;
        
        if ($id != LoginManager::getSession()) {
            $usersManager = new UsersManager();
            
            $result = $usersManager->delete($id);
            
            if ($result === FALSE) {
                $messages = 'No se puede borrar un usuario con entradas publicadas.';
            } elseif ($result === 1) {
                $typeMessage = Messages::TYPE_SUCCESS;
                $messages    = 'Usuario borrado correctamente.';
            }
        }
        
        Messages::addMessage($messages, $typeMessage);
        parent::delete($id);
    }
    
}
