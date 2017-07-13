<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\install;

use SoftnCMS\controllers\ControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\ManagerAbstract;
use SoftnCMS\models\managers\InstallManager;
use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController {
    
    public function index() {
        if (Form::submit(ManagerAbstract::FORM_SUBMIT)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $installManager = new InstallManager();
                
                if ($installManager->checkConnection($form) && $installManager->createFileConfig($form)) {
                    $message = 'El proceso de instalación se completo correctamente.';
                    
                    if ($installManager->createTables()) {
                        Messages::addSessionMessage($message, Messages::TYPE_SUCCESS);
                        Util::redirect(Arrays::get($form, InstallManager::INSTALL_SITE_URL) . 'login');
                    } else {
                        $message = 'Error al crear las tablas de la base de datos.';
                        Messages::addSessionMessage($message, Messages::TYPE_DANGER);
                    }
                }
            }
        }
        
        $optionsManager = new OptionsManager();
        ViewController::sendViewData('siteUrl', $optionsManager->getSiteUrl());
        ViewController::sendViewData('charset', 'utf8');
        ViewController::sendViewData('prefix', 'sn_');
        ViewController::sendViewData('host', 'localhost');
        ViewController::view('index');
    }
    
    private function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        return $inputs;
    }
    
    private function filterInputs() {
        Form::setINPUT([
            InputUrlBuilder::init(InstallManager::INSTALL_SITE_URL)
                           ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_DB_NAME)
                                    ->setWithoutSpace(TRUE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_DB_USER)
                                    ->setWithoutSpace(TRUE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_DB_PASSWORD)
                                    ->setWithoutSpace(TRUE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_HOST)
                                    ->setWithoutSpace(TRUE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_PREFIX)
                                    ->setWithoutSpace(TRUE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(InstallManager::INSTALL_CHARSET)
                                    ->setWithoutSpace(TRUE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
}
