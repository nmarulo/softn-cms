<?php
/**
 * LicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class LicenseController
 * @author Nicolás Marulanda P.
 */
class LicenseController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $licensesManager = new LicensesManager();
                $license         = Arrays::get($form, 'license');
                
                if ($licensesManager->create($license)) {
                    Messages::addSuccess(__('Permiso creado correctamente.'));
                    Util::redirect(Router::getSiteURL() . 'admin/license');
                }
            }
            
            Messages::addDanger(__('Error al crear el permiso.'));
        }
        
        ViewController::sendViewData('license', new License());
        ViewController::sendViewData('title', __('Crear nuevo permiso'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $license = new License();
        $license->setId(Arrays::get($inputs, LicensesManager::ID));
        $license->setLicenseName(Arrays::get($inputs, LicensesManager::LICENSE_NAME));
        $license->setLicenseDescription(Arrays::get($inputs, LicensesManager::LICENSE_DESCRIPTION));
        
        return ['license' => $license];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_NAME)
                                    ->setAccents(FALSE)
                                    ->setWithoutSpace(TRUE)
                                    ->setReplaceSpace('_')
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $licensesManager = new LicensesManager();
        $license         = $licensesManager->searchById($id);
        
        if (empty($license)) {
            Messages::addDanger(__('El permiso no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/license');
        } elseif (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos del permiso.'));
            } else {
                $license = Arrays::get($form, 'license');
                
                if ($licensesManager->update($license)) {
                    Messages::addSuccess(__('Permiso actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el permiso.'));
                }
            }
        }
        
        ViewController::sendViewData('license', $license);
        ViewController::sendViewData('title', __('Actualizar permiso'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        $licensesManager = new LicensesManager();
        
        if (empty($licensesManager->delete($id))) {
            Messages::addDanger(__('Error al borrar el permiso.'));
        } else {
            Messages::addSuccess(__('Permiso borrado correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters         = [];
        $licensesManager = new LicensesManager();
        $count           = $licensesManager->count();
        $pagination      = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('licenses', $licensesManager->read($filters));
    }
}
