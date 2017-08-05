<?php
/**
 * SidebarController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
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
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $sidebarsManager = new SidebarsManager();
                $sidebar         = Arrays::get($form, 'sidebar');
                
                if ($sidebarsManager->create($sidebar)) {
                    Messages::addSuccess('Barra lateral creado correctamente.', TRUE);
                    Util::redirect(Router::getSiteURL() . 'admin/sidebar');
                }
            }
            
            Messages::addDanger('Error al crear el sidebar.');
        }
        
        ViewController::sendViewData('sidebar', new Sidebar());
        ViewController::sendViewData('title', 'Crear nuevo');
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $sidebar = new Sidebar();
        $sidebar->setId(Arrays::get($inputs, SidebarsManager::ID));
        $sidebar->setSidebarTitle(Arrays::get($inputs, SidebarsManager::SIDEBAR_TITLE));
        $sidebar->setSidebarContents(Arrays::get($inputs, SidebarsManager::SIDEBAR_CONTENTS));
        $sidebar->setSidebarPosition(NULL);
        
        return ['sidebar' => $sidebar];
    }
    
    protected function filterInputs() {
        Form::setINPUT([
            InputIntegerBuilder::init(SidebarsManager::ID)
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
            Messages::addDanger('La barra lateral no existe.', TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/sidebar');
        } else {
            if (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
                $form = $this->form();
                
                if (empty($form)) {
                    Messages::addDanger('Error en los campos del a barra lateral.');
                } else {
                    $sidebar = Arrays::get($form, 'sidebar');
                    
                    if ($sidebarsManager->update($sidebar)) {
                        Messages::addSuccess('Barra lateral actualizada correctamente.');
                    } else {
                        Messages::addDanger('Error al actualizar la barra lateral.');
                    }
                }
            }
            
            ViewController::sendViewData('sidebar', $sidebar);
            ViewController::sendViewData('title', 'Actualizar');
            ViewController::view('form');
        }
    }
    
    public function delete($id) {
        $sidebarsManager = new SidebarsManager();
        
        if (empty($sidebarsManager->delete($id))) {
            Messages::addDanger('Error al borrar la barra lateral.');
        } else {
            Messages::addSuccess('Barra lateral borrada correctamente.');
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $sidebarsManager = new SidebarsManager();
        ViewController::sendViewData('sidebars', $sidebarsManager->read());
    }
    
}
