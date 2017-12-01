<?php
/**
 * RegisterController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\rute\Router;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class RegisterController
 * @author Nicolás Marulanda P.
 */
class RegisterController extends ControllerAbstract {
    
    public function index() {
        $this->register();
        $this->sendDataView(['urlLogin' => Router::getSiteURL() . 'login']);
        $this->view();
    }
    
    private function register() {
        if ($this->checkSubmit(Constants::FORM_SUBMIT)) {
            if ($this->isValidForm()) {
                $usersManager = new UsersManager($this->getConnectionDB());
                $user         = $this->getForm('user');
                
                if ($usersManager->create($user)) {
                    Messages::addSuccess(__('Usuario registrado correctamente.'), TRUE);
                    $this->redirect('login');
                }
            }
            
            Messages::addDanger(__('Error al registrar el usuario.'));
        }
    }
    
    protected function formToObject() {
        $pass  = $this->getInput(UsersManager::USER_PASSWORD);
        $passR = $this->getInput(UsersManager::USER_PASSWORD_REWRITE);
        
        if ($pass != $passR) {
            return FALSE;
        }
        
        $optionsManager = new OptionsManager($this->getConnectionDB());
        $pass           = Util::encrypt($pass, LOGGED_KEY);
        $user           = new User();
        $user->setUserPassword($pass);
        $user->setUserLogin($this->getInput(UsersManager::USER_LOGIN));
        $user->setUserEmail($this->getInput(UsersManager::USER_EMAIL));
        $user->setUserRegistered(Util::dateNow());
        $user->setUserName($user->getUserLogin());
        $user->setUserPostCount(0);
        $user->setProfileId($optionsManager->searchByName(OptionConstants::DEFAULT_PROFILE)
                                           ->getOptionValue());
        
        return ['user' => $user];
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
            InputAlphanumericBuilder::init(UsersManager::USER_PASSWORD_REWRITE)
                                    ->build(),
            InputEmailBuilder::init(UsersManager::USER_EMAIL)
                             ->build(),
        ];
    }
    
}
