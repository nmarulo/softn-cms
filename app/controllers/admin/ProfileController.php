<?php
/**
 * ProfileController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class ProfileController
 * @author Nicolás Marulanda P.
 */
class ProfileController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $form = $this->form();
            
            if (!empty($form)) {
                $profilesManager = new ProfilesManager();
                $profile         = Arrays::get($form, 'profile');
                
                if ($profilesManager->create($profile)) {
                    Messages::addSuccess(__('Perfil creado correctamente.'));
                    Util::redirect(Router::getSiteURL() . 'admin/profile');
                }
            }
            
            Messages::addDanger(__('Error al crear el perfil.'));
        }
        
        ViewController::sendViewData('profile', new Profile());
        ViewController::sendViewData('title', __('Crear nuevo perfil'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $license = new Profile();
        $license->setId(Arrays::get($inputs, ProfilesManager::ID));
        $license->setProfileName(Arrays::get($inputs, ProfilesManager::PROFILE_NAME));
        $license->setProfileDescription(Arrays::get($inputs, ProfilesManager::PROFILE_DESCRIPTION));
        
        return ['license' => $license];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_NAME)
                                    ->setAccents(FALSE)
                                    ->setWithoutSpace(TRUE)
                                    ->setReplaceSpace('_')
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    public function update($id) {
        $profilesManager = new ProfilesManager();
        $profile         = $profilesManager->searchById($id);
        
        if (empty($profile)) {
            Messages::addDanger(__('El perfil no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/profile');
        } elseif (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos del perfil.'));
            } else {
                $profile = Arrays::get($form, 'profile');
                
                if ($profilesManager->update($profile)) {
                    Messages::addSuccess(__('Perfil actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el perfil.'));
                }
            }
        }
        
        ViewController::sendViewData('profile', $profile);
        ViewController::sendViewData('title', __('Actualizar perfil'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        $profilesManager = new ProfilesManager();
        
        if (empty($profilesManager->delete($id))) {
            Messages::addDanger(__('Error al borrar el perfil.'));
        } else {
            Messages::addSuccess(__('Perfil borrado correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters         = [];
        $profilesManager = new ProfilesManager();
        $count           = $profilesManager->count();
        $pagination      = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('profiles', $profilesManager->read($filters));
    }
    
}
