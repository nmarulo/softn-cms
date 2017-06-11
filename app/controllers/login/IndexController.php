<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\ManagerAbstract;
use SoftnCMS\models\managers\LoginManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\managers\UsersManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        $this->login();
        $this->read();
        ViewController::view('index');
    }
    
    private function login() {
        if (Form::submit(ManagerAbstract::FORM_SUBMIT)) {
            $message     = 'Usuario o/y contraseña incorrecto(s).';
            $typeMessage = Messages::TYPE_DANGER;
            $form        = $this->form();
            
            if (!empty($form)) {
                $user       = Arrays::get($form, 'user');
                $rememberMe = Arrays::get($form, 'rememberMe');
                
                if (LoginManager::login($user, $rememberMe)) {
                    $message        = 'Inicio de sesión correcto.';
                    $typeMessage    = Messages::TYPE_SUCCESS;
                    $optionsManager = new OptionsManager();
                    Messages::addSessionMessage($message, $typeMessage);
                    Util::redirect($optionsManager->getSiteUrl(), 'admin');
                }
            }
            
            Messages::addMessage($message, $typeMessage);
        }
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if ($inputs === FALSE) {
            return FALSE;
        }
        
        $rememberMe = Arrays::get($inputs, UsersManager::USER_REMEMBER_ME);
        $pass       = Arrays::get($inputs, UsersManager::USER_PASSWORD);
        $user       = new User();
        $user->setUserLogin(Arrays::get($inputs, UsersManager::USER_LOGIN));
        $user->setUserPassword(Util::encrypt($pass, LOGGED_KEY));
        
        return [
            'user'       => $user,
            'rememberMe' => $rememberMe,
        ];
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
            InputBooleanBuilder::init(UsersManager::USER_REMEMBER_ME)
                               ->setRequire(FALSE)
                               ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    protected function read() {
        $optionsManager = new OptionsManager();
        $siteUrl        = $optionsManager->searchByName(OPTION_SITE_URL)
                                         ->getOptionValue();
        $urlRegister    = $siteUrl . 'login/register';
        ViewController::sendViewData('siteUrl', $siteUrl);
        ViewController::sendViewData('urlRegister', $urlRegister);
    }
    
}
