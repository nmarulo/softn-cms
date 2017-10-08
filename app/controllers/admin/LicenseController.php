<?php
/**
 * LicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\models\tables\ProfileLicense;
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
        if (Form::submit(Constants::FORM_CREATE)) {
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
        ViewController::sendViewData('isUpdate', FALSE);
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
        $profiles = Arrays::get($inputs, ProfilesLicensesManager::PROFILE_ID);
        $license->setId(Arrays::get($inputs, LicensesManager::COLUMN_ID));
        $license->setLicenseName(Arrays::get($inputs, LicensesManager::LICENSE_NAME));
        $license->setLicenseDescription(Arrays::get($inputs, LicensesManager::LICENSE_DESCRIPTION));
        
        return [
            'license'  => $license,
            'profiles' => $profiles,
        ];
    }
    
    protected function filterInputs() {
        Form::setInput([
            InputIntegerBuilder::init(LicensesManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_NAME)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(LicensesManager::LICENSE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputListIntegerBuilder::init(ProfilesLicensesManager::PROFILE_ID)
                                   ->build(),
        ]);
        
        return Form::inputFilter();
    }
    
    private function createOrDeleteProfiles($profilesId, $licenseId) {
        $profilesLicensesManager = new ProfilesLicensesManager();
        
        if (empty($profilesId)) {
            if ($profilesLicensesManager->deleteAllByLicenseId($licenseId) === FALSE) {
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
                $profileLicense = new ProfileLicense();
                $profileLicense->setProfileId($profileId);
                $profileLicense->setLicenseId($licenseId);
                
                return $profileLicense;
            }, $newSelectedProfilesId);
            
            //Obtengo los identificadores de los perfiles que no se han seleccionado.
            $profilesIdNotSelected = array_filter($selectedProfilesId, function($selectedProfileId) use ($profilesId) {
                return !Arrays::valueExists($profilesId, $selectedProfileId);
            });
            
            if (!empty($profilesIdNotSelected) && $profilesLicensesManager->deleteAllProfilesByLicenseId($profilesIdNotSelected, $licenseId) === FALSE) {
                Messages::addDanger(__('Error al borrar los perfiles.'));
            }
            
            array_walk($newLicensesProfiles, function(ProfileLicense $profileLicense) use (&$numError, $profilesLicensesManager) {
                if (!$profilesLicensesManager->create($profileLicense)) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los perfiles.'));
            }
        }
    }
    
    private function getProfilesIdByLicenseId($licenseId) {
        $licensesProfilesManager = new ProfilesLicensesManager();
        $licenseProfiles         = $licensesProfilesManager->searchAllByLicenseId($licenseId);
        
        return array_map(function(ProfileLicense $profileLicense) {
            return $profileLicense->getProfileId();
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
        } elseif (Form::submit(Constants::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos del permiso.'));
            } else {
                $license = Arrays::get($form, 'license');
                
                if ($licensesManager->updateByColumnId($license)) {
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
        ViewController::sendViewData('isUpdate', TRUE);
        ViewController::sendViewData('selectedProfilesId', $this->getProfilesIdByLicenseId($id));
        ViewController::sendViewData('license', $license);
        ViewController::sendViewData('title', __('Actualizar permiso'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        $licensesManager = new LicensesManager();
        
        if (empty($licensesManager->deleteById($id))) {
            Messages::addDanger(__('Error al borrar el permiso.'));
        } else {
            Messages::addSuccess(__('Permiso borrado correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $licensesManager = new LicensesManager();
        $count           = $licensesManager->count();
        $limit           = parent::pagination($count);
        
        ViewController::sendViewData('licenses', $licensesManager->searchAll($limit));
    }
}
