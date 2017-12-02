<?php
/**
 * IndexController.php
 */

namespace SoftnCMS\controllers\install;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\InstallManager;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputUrlBuilder;
use SoftnCMS\util\Messages;

/**
 * Class IndexController
 * @author Nicolás Marulanda P.
 */
class IndexController extends ControllerAbstract {
    
    public function index() {
        if ($this->checkSubmit(Constants::FORM_SUBMIT) && $this->isValidForm() && $this->check()) {
            Messages::addSuccess(__('El proceso de instalación se completo correctamente.'), TRUE);
            $this->redirectRoute('login', 'register', 'index');
        }
        
        $this->sendDataView([
            'charset' => 'utf8',
            'prefix'  => 'sn_',
            'host'    => 'localhost',
        ]);
        $this->view();
    }
    
    private function check() {
        $installManager = new InstallManager();
        
        if (!$installManager->checkConnection($this->getInputs())) {
            Messages::addDanger(__('Error al establecer la conexión con la base de datos.'));
            
            return FALSE;
        }
        
        if (!$installManager->createFileConfig($this->getInputs())) {
            Messages::addDanger(__('No es posible escribir en el directorio %1$s.', ABSPATH));
            
            return FALSE;
        }
        
        if (!$installManager->createTables()) {
            Messages::addDanger(__('Error al crear las tablas de la base de datos.'));
            
            return FALSE;
        }
        
        return TRUE;
    }
    
    protected function formToObject() {
        //Se devuelve true para que pase la validación del formulario.
        return TRUE;
    }
    
    protected function formInputsBuilders() {
        return [
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
        ];
    }
    
}
