<?php
/**
 * ProfileController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\LicensesProfilesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\LicenseProfile;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
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
                    $licenses = Arrays::get($form, 'licenses');
                    $this->createOrDeleteLicenses($licenses, $profilesManager->getLastInsertId());
                    Messages::addSuccess(__('Perfil creado correctamente.'));
                    Util::redirect(Router::getSiteURL() . 'admin/profile');
                }
            }
            
            Messages::addDanger(__('Error al crear el perfil.'));
        }
        
        $this->sendViewLicenses();
        ViewController::sendViewData('profile', new Profile());
        ViewController::sendViewData('title', __('Crear nuevo perfil'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $profile  = new Profile();
        $licenses = Arrays::get($inputs, LicensesProfilesManager::LICENSE_ID);
        $profile->setId(Arrays::get($inputs, ProfilesManager::ID));
        $profile->setProfileName(Arrays::get($inputs, ProfilesManager::PROFILE_NAME));
        $profile->setProfileDescription(Arrays::get($inputs, ProfilesManager::PROFILE_DESCRIPTION));
        
        return [
            'profile'  => $profile,
            'licenses' => $licenses,
        ];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(ProfilesManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_NAME)
                                    ->setAccents(FALSE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputListIntegerBuilder::init(LicensesProfilesManager::LICENSE_ID)
                                   ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function sendViewLicenses() {
        $licensesManager = new LicensesManager();
        ViewController::sendViewData('licenses', $licensesManager->read());
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
                    $profile  = $profilesManager->searchById($id);
                    $licenses = Arrays::get($form, 'licenses');
                    $this->createOrDeleteLicenses($licenses, $id);
                    Messages::addSuccess(__('Perfil actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el perfil.'));
                }
            }
        }
        
        $this->sendViewLicenses();
        ViewController::sendViewData('selectedLicensesId', $this->getLicensesIdByProfileId($id));
        ViewController::sendViewData('profile', $profile);
        ViewController::sendViewData('title', __('Actualizar perfil'));
        ViewController::view('form');
    }
    
    private function createOrDeleteLicenses($licensesId, $profileId) {
        $licensesProfilesManager = new LicensesProfilesManager();
        
        if (empty($licensesId)) {
            if ($licensesProfilesManager->deleteAllByProfileId($profileId) === FALSE) {
                Messages::addDanger(__('Error al borrar los permisos.'));
            }
        } else {
            $selectedLicensesId = $this->getLicensesIdByProfileId($profileId);
            $numError           = 0;
            //Obtengo los identificadores de los nuevos permisos.
            $newSelectedLicensesId = array_filter($licensesId, function($licenseId) use ($selectedLicensesId) {
                return !Arrays::valueExists($selectedLicensesId, $licenseId);
            });
            $newLicensesProfiles   = array_map(function($licenseId) use ($profileId) {
                $licenseProfile = new LicenseProfile();
                $licenseProfile->setProfileId($profileId);
                $licenseProfile->setLicenseId($licenseId);
                
                return $licenseProfile;
            }, $newSelectedLicensesId);
            
            //Obtengo los identificadores de los permisos que no se han seleccionado.
            $licensesIdNotSelected = array_filter($selectedLicensesId, function($selectedLicenseId) use ($licensesId) {
                return !Arrays::valueExists($licensesId, $selectedLicenseId);
            });
            
            if (!empty($licensesIdNotSelected) && $licensesProfilesManager->deleteAllLicensesByProfileId($licensesIdNotSelected, $profileId) === FALSE) {
                Messages::addDanger(__('Error al borrar los permisos.'));
            }
            
            array_walk($newLicensesProfiles, function(LicenseProfile $licenseProfile) use (&$numError, $licensesProfilesManager) {
                if (!$licensesProfilesManager->create($licenseProfile)) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los permisos.'));
            }
        }
    }
    
    private function getLicensesIdByProfileId($profileId) {
        $licensesProfilesManager = new LicensesProfilesManager();
        $licensesProfile         = $licensesProfilesManager->searchAllByProfileId($profileId);
        
        return array_map(function(LicenseProfile $licenseProfile) {
            return $licenseProfile->getLicenseId();
        }, $licensesProfile);
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
