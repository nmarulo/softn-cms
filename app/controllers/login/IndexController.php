<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        $this->login();
        $this->sendDataView([
            'urlRegister' => $this->getRequest()
                                  ->getSiteUrl() . 'login/register',
        ]);
        $this->view();
    }
    
    private function login() {
        if ($this->checkSubmit(Constants::FORM_SUBMIT)) {
            if ($this->isValidForm()) {
                $user       = $this->getForm('user');
                $rememberMe = $this->getForm('rememberMe');
                
                if (LoginManager::login($user, $rememberMe, $this->getConnectionDB())) {
                    Messages::addSuccess(__('Inicio de sesión correcto.'), TRUE);
                    $this->redirect('admin');
                }
            }
            
            Messages::addDanger(__('Usuario o/y contraseña incorrecto(s).'));
        }
    }
    
    protected function formToObject() {
        $pass = $this->getInput(UsersManager::USER_PASSWORD);
        $user = new User();
        $user->setUserLogin($this->getInput(UsersManager::USER_LOGIN));
        $user->setUserPassword(Util::encrypt($pass, LOGGED_KEY));
        
        return [
            'user'       => $user,
            'rememberMe' => $this->getInput(UsersManager::USER_REMEMBER_ME),
        ];
    }
    
    protected function formInputsBuilders() {
        return [
            InputAlphanumericBuilder::init(UsersManager::USER_LOGIN)
                                    ->setAccents(FALSE)
                                    ->setWithoutSpace(TRUE)
                                    ->setReplaceSpace('')
                                    ->build(),
            InputAlphanumericBuilder::init(UsersManager::USER_PASSWORD)
                                    ->build(),
            InputBooleanBuilder::init(UsersManager::USER_REMEMBER_ME)
                               ->setRequire(FALSE)
                               ->build(),
        ];
    }
    
}
