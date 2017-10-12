<?php
/**
 * SidebarController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\SidebarsManager;
use SoftnCMS\models\tables\Sidebar;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class SidebarController
 * @author Nicolás Marulanda P.
 */
class SidebarController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(Constants::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $sidebarsManager = new SidebarsManager();
                $sidebar         = Arrays::get($form, 'sidebar');
                
                if ($sidebarsManager->create($sidebar)) {
                    Messages::addSuccess(__('Barra lateral creado correctamente.'), TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/sidebar');
                }
            }
            
            Messages::addDanger(__('Error al crear el sidebar.'));
        }
        
        ViewController::sendViewData('isUpdate', FALSE);
        ViewController::sendViewData('sidebar', new Sidebar());
        ViewController::sendViewData('title', __('Crear nuevo'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $sidebar = new Sidebar();
        $sidebar->setId(Arrays::get($inputs, SidebarsManager::COLUMN_ID));
        $sidebar->setSidebarTitle(Arrays::get($inputs, SidebarsManager::SIDEBAR_TITLE));
        $sidebar->setSidebarContents(Arrays::get($inputs, SidebarsManager::SIDEBAR_CONTENTS));
        $sidebar->setSidebarPosition(NULL);
        
        return ['sidebar' => $sidebar];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(SidebarsManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(SidebarsManager::SIDEBAR_TITLE)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputAlphanumericBuilder::init(SidebarsManager::SIDEBAR_CONTENTS)
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $sidebarsManager = new SidebarsManager();
        $sidebar         = $sidebarsManager->searchById($id);
        
        if (empty($sidebar)) {
            Messages::addDanger(__('La barra lateral no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/sidebar');
        } else {
            if (Form::submit(Constants::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger(__('Error en los campos del a barra lateral.'));
                } else {
                    $sidebar = Arrays::get($form, 'sidebar');
                    
                    if ($sidebarsManager->updateByColumnId($sidebar)) {
                        Messages::addSuccess(__('Barra lateral actualizada correctamente.'));
                    } else {
                        Messages::addDanger(__('Error al actualizar la barra lateral.'));
                    }
                }
            }
            
            ViewController::sendViewData('isUpdate', TRUE);
            ViewController::sendViewData('sidebar', $sidebar);
            ViewController::sendViewData('title', __('Actualizar'));
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $sidebarsManager = new SidebarsManager();
        
        if (empty($sidebarsManager->deleteById($id))) {
            Messages::addDanger(__('Error al borrar la barra lateral.'));
        } else {
            Messages::addSuccess(__('Barra lateral borrada correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $sidebarsManager = new SidebarsManager();
        ViewController::sendViewData('sidebars', $sidebarsManager->searchAll());
    }
    
}
