<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\login;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
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
        if (Form::submit(Constants::FORM_SUBMIT)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $user       = Arrays::get($form, 'user');
                $rememberMe = Arrays::get($form, 'rememberMe');
                
                if (LoginManager::login($user, $rememberMe)) {
                    $optionsManager = new OptionsManager();
                    Messages::addSuccess(__('Inicio de sesión correcto.'), TRUE);
                    Util::redirect($optionsManager->getSiteUrl(), 'admin');
                }
            }
            
            Messages::addDanger(__('Usuario o/y contraseña incorrecto(s).'));
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
        Form::setInput([
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
        $siteUrl        = $optionsManager->getSiteUrl();
        $urlRegister    = $siteUrl . 'login/register';
        ViewController::sendViewData('siteUrl', $siteUrl);
        ViewController::sendViewData('urlRegister', $urlRegister);
    }
    
}
