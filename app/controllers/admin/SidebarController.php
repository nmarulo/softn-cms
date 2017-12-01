<?php
/**
 * SidebarController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class SidebarController
 * @author Nicolás Marulanda P.
 */
class SidebarController extends ControllerAbstract {
    
    public function index() {
        $sidebarsManager = new SidebarsManager($this->getConnectionDB());
        //No necesita paginación, por el momento.
        $this->sendDataView(['sidebars' => $sidebarsManager->searchAll()]);
        $this->view();
    }
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $sidebarsManager = new SidebarsManager($this->getConnectionDB());
                $sidebar         = $this->getForm('sidebar');
                
                if ($sidebarsManager->create($sidebar)) {
                    Messages::addSuccess(__('Barra lateral creado correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al crear el sidebar.'));
        }
        
        $this->sendDataView([
            'isUpdate' => FALSE,
            'sidebar'  => new Sidebar(),
            'title'    => __('Crear nuevo'),
        ]);
        $this->view('form');
    }
    
    public function update($id) {
        $sidebarsManager = new SidebarsManager($this->getConnectionDB());
        $sidebar         = $sidebarsManager->searchById($id);
        
        if (empty($sidebar)) {
            Messages::addDanger(__('La barra lateral no existe.'), TRUE);
            $this->redirectToAction('index');
        } else {
            if ($this->checkSubmit(Constants::FORM_UPDATE)) {
                if ($this->isValidForm()) {
                    $sidebar = $this->getForm('sidebar');
                    
                    if ($sidebarsManager->updateByColumnId($sidebar)) {
                        Messages::addSuccess(__('Barra lateral actualizada correctamente.'));
                    } else {
                        Messages::addDanger(__('Error al actualizar la barra lateral.'));
                    }
                } else {
                    Messages::addDanger(__('Error en los campos del a barra lateral.'));
                }
            }
            
            $this->sendDataView([
                'isUpdate' => TRUE,
                'sidebar'  => $sidebar,
                'title'    => __('Actualizar'),
            ]);
            $this->view('form');
        }
    }
    
    public function delete($id) {
        if (Token::check()) {
            $sidebarsManager = new SidebarsManager($this->getConnectionDB());
            $result          = $sidebarsManager->deleteById($id);
            $rowCount        = $sidebarsManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('La barra lateral no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Barra lateral borrada correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la barra lateral.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $sidebar = new Sidebar();
        $sidebar->setId($this->getInput(SidebarsManager::COLUMN_ID));
        $sidebar->setSidebarTitle($this->getInput(SidebarsManager::SIDEBAR_TITLE));
        $sidebar->setSidebarContents($this->getInput(SidebarsManager::SIDEBAR_CONTENTS));
        $sidebar->setSidebarPosition(NULL);
        
        return ['sidebar' => $sidebar];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(SidebarsManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(SidebarsManager::SIDEBAR_TITLE)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputAlphanumericBuilder::init(SidebarsManager::SIDEBAR_CONTENTS)
                                    ->setRequire(FALSE)
                                    ->build(),
        ];
    }
}
