<?php
/**
 * LicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\LicensesProfilesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\models\tables\LicenseProfile;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
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
                    $profiles = Arrays::get($form, 'profiles');
                    $this->createOrDeleteProfiles($profiles, $licensesManager->getLastInsertId());
                    Messages::addSuccess(__('Permiso creado correctamente.'));
                    Util::redirect(Router::getSiteURL() . 'admin/license');
                }
            }
            
            Messages::addDanger(__('Error al crear el permiso.'));
        }
        
        $this->sendViewProfiles();
        ViewController::sendViewData('license', new License());
        ViewController::sendViewData('title', __('Crear nuevo permiso'));
        ViewController::view('form');
    }
    
    protected function form() {
        $inputs = $this->filterInputs();
        
        if (empty($inputs)) {
            return FALSE;
        }
        
        $license  = new License();
        $profiles = Arrays::get($inputs, LicensesProfilesManager::PROFILE_ID);
        $license->setId(Arrays::get($inputs, LicensesManager::ID));
        $license->setLicenseName(Arrays::get($inputs, LicensesManager::LICENSE_NAME));
        $license->setLicenseDescription(Arrays::get($inputs, LicensesManager::LICENSE_DESCRIPTION));
        
        return [
            'license'  => $license,
            'profiles' => $profiles,
        ];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(LicensesManager::ID)
                               ->build(),
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_NAME)
                                    ->setAccents(FALSE)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputListIntegerBuilder::init(LicensesProfilesManager::PROFILE_ID)
                                   ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function createOrDeleteProfiles($profilesId, $licenseId) {
        $licensesProfilesManager = new LicensesProfilesManager();
        
        if (empty($profilesId)) {
            if ($licensesProfilesManager->deleteAllByLicenseId($licenseId) === FALSE) {
                Messages::addDanger(__('Error al borrar los perfiles.'));
            }
        } else {
            $selectedProfilesId = $this->getProfilesIdByLicenseId($licenseId);
            $numError           = 0;
            //Obtengo los identificadores de los nuevos perfiles.
            $newSelectedProfilesId = array_filter($profilesId, function($profileId) use ($selectedProfilesId) {
                return !Arrays::valueExists($selectedProfilesId, $profileId);
            });
            $newLicensesProfiles   = array_map(function($profileId) use ($licenseId) {
                $licenseProfile = new LicenseProfile();
                $licenseProfile->setProfileId($profileId);
                $licenseProfile->setLicenseId($licenseId);
                
                return $licenseProfile;
            }, $newSelectedProfilesId);
            
            //Obtengo los identificadores de los perfiles que no se han seleccionado.
            $profilesIdNotSelected = array_filter($selectedProfilesId, function($selectedProfileId) use ($profilesId) {
                return !Arrays::valueExists($profilesId, $selectedProfileId);
            });
            
            if (!empty($profilesIdNotSelected) && $licensesProfilesManager->deleteAllProfilesByLicenseId($profilesIdNotSelected, $licenseId) === FALSE) {
                Messages::addDanger(__('Error al borrar los perfiles.'));
            }
            
            array_walk($newLicensesProfiles, function(LicenseProfile $licenseProfile) use (&$numError, $licensesProfilesManager) {
                if (!$licensesProfilesManager->create($licenseProfile)) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los perfiles.'));
            }
        }
    }
    
    private function getProfilesIdByLicenseId($licenseId) {
        $licensesProfilesManager = new LicensesProfilesManager();
        $licenseProfiles         = $licensesProfilesManager->searchAllByLicenseId($licenseId);
        
        return array_map(function(LicenseProfile $licenseProfile) {
            return $licenseProfile->getProfileId();
        }, $licenseProfiles);
    }
    
    private function sendViewProfiles() {
        $profilesManager = new ProfilesManager();
        ViewController::sendViewData('profiles', $profilesManager->read());
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
                    $license  = $licensesManager->searchById($id);
                    $profiles = Arrays::get($form, 'profiles');
                    $this->createOrDeleteProfiles($profiles, $id);
                    Messages::addSuccess(__('Permiso actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el permiso.'));
                }
            }
        }
        
        $this->sendViewProfiles();
        ViewController::sendViewData('selectedProfilesId', $this->getProfilesIdByLicenseId($id));
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
