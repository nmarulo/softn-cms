<?php
/**
 * RegisterController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\ManagerAbstract;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputEmailBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class RegisterController
 * @author Nicolás Marulanda P.
 */
class RegisterController extends ControllerAbstract {
    
    public function index() {
        $this->register();
        $this->read();
        ViewController::view('index');
    }
    
    private function register() {
        if (Form::submit(ManagerAbstract::FORM_SUBMIT)) {
            $message     = 'Error al registrar el usuario.';
            $typeMessage = Messages::TYPE_DANGER;
            $form        = $this->form();
            
            if (!empty($form)) {
                $usersManager = new UsersManager();
                $user         = Arrays::get($form, 'user');
                
                if ($usersManager->create($user)) {
                    $message        = 'Usuario registrado correctamente.';
                    $typeMessage    = Messages::TYPE_SUCCESS;
                    $optionsManager = new OptionsManager();
                    Messages::addSessionMessage($message, $typeMessage);
                    Util::redirect($optionsManager->getSiteUrl(), 'login');
                }
            }
            
            Messages::addMessage($message, $typeMessage);
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $pass  = Arrays::get($inputs, UsersManager::USER_PASSWORD);
        $passR = Arrays::get($inputs, UsersManager::USER_PASSWORD_REWRITE);
        
        if ($pass != $passR) {
            return FALSE;
        }
        
        $pass = Util::encrypt($pass, LOGGED_KEY);
        $user = new User();
        $user->setUserPassword($pass);
        $user->setUserLogin(Arrays::get($inputs, UsersManager::USER_LOGIN));
        $user->setUserEmail(Arrays::get($inputs, UsersManager::USER_EMAIL));
        $user->setUserRegistered(Util::dateNow());
        $user->setUserName($user->getUserLogin());
        $user->setUserRol(0);
        $user->setUserPostCount(0);
        
        return ['user' => $user];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
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
        ]);
        
        return Form::inputFilter();
    }
    
    protected function read() {
        $optionsManager = new OptionsManager();
        $siteUrl        = $optionsManager->searchByName(OPTION_SITE_URL)
                                         ->getOptionValue();
        $urlLogin       = $siteUrl . 'login';
        ViewController::sendViewData('siteUrl', $siteUrl);
        ViewController::sendViewData('urlLogin', $urlLogin);
    }
    
}
