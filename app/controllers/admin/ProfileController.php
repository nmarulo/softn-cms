<?php
/**
 * ProfileController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\Profile;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class ProfileController
 * @author Nicolás Marulanda P.
 */
class ProfileController extends ControllerAbstract {
    
    public function index() {
        $profilesManager = new ProfilesManager($this->getConnectionDB());
        $count           = $profilesManager->count();
        
        $this->sendDataView([
            'profiles' => $profilesManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $profilesManager = new ProfilesManager($this->getConnectionDB());
                $profile         = $this->getForm('profile');
                
                if ($profilesManager->create($profile)) {
                    $licenses = $this->getForm('licenses');
                    $this->createOrDeleteLicenses($licenses, $profilesManager->getLastInsertId());
                    Messages::addSuccess(__('Perfil creado correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al crear el perfil.'));
        }
        
        $this->sendViewLicenses();
        $this->sendDataView([
            'isUpdate' => FALSE,
            'profile'  => new Profile(),
            'title'    => __('Crear nuevo perfil'),
        ]);
        $this->view('form');
    }
    
    private function createOrDeleteLicenses($licensesId, $profileId) {
        $licensesProfilesManager = new ProfilesLicensesManager($this->getConnectionDB());
        
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
                $licenseProfile = new ProfileLicense();
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
            
            array_walk($newLicensesProfiles, function(ProfileLicense $licenseProfile) use (&$numError, $licensesProfilesManager) {
                if ($licensesProfilesManager->create($licenseProfile) === FALSE) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los permisos.'));
            }
        }
    }
    
    private function getLicensesIdByProfileId($profileId) {
        $licensesProfilesManager = new ProfilesLicensesManager($this->getConnectionDB());
        $licensesProfile         = $licensesProfilesManager->searchAllByProfileId($profileId);
        
        return array_map(function(ProfileLicense $licenseProfile) {
            return $licenseProfile->getLicenseId();
        }, $licensesProfile);
    }
    
    private function sendViewLicenses() {
        $licensesManager = new LicensesManager($this->getConnectionDB());
        $this->sendDataView(['licenses' => $licensesManager->searchAll()]);
    }
    
    public function update($id) {
        $profilesManager = new ProfilesManager($this->getConnectionDB());
        $profile         = $profilesManager->searchById($id);
        
        if (empty($profile)) {
            Messages::addDanger(__('El perfil no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $profile = $this->getForm('profile');
                
                if ($profilesManager->update($profile)) {
                    $profile  = $profilesManager->searchById($id);
                    $licenses = $this->getForm('licenses');
                    $this->createOrDeleteLicenses($licenses, $id);
                    Messages::addSuccess(__('Perfil actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el perfil.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos del perfil.'));
            }
        }
        
        $this->sendViewLicenses();
        $this->sendDataView([
            'isUpdate'           => TRUE,
            'selectedLicensesId' => $this->getLicensesIdByProfileId($id),
            'profile'            => $profile,
            'title'              => __('Actualizar perfil'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $profilesManager = new ProfilesManager($this->getConnectionDB());
            $result          = $profilesManager->deleteById($id);
            $rowCount        = $profilesManager->getRowCount();
            
            if ($result === FALSE) {
                Messages::addDanger(__('No puedes borrar un perfil con usuarios asociados.'), TRUE);
            } elseif ($rowCount === 0) {
                Messages::addDanger(__('El perfil no existe.'), TRUE);
            } else {
                Messages::addSuccess(__('Perfil borrado correctamente.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $profile  = new Profile();
        $licenses = $this->getInput(ProfilesLicensesManager::LICENSE_ID);
        $profile->setId($this->getInput(ProfilesManager::COLUMN_ID));
        $profile->setProfileName($this->getInput(ProfilesManager::PROFILE_NAME));
        $profile->setProfileDescription($this->getInput(ProfilesManager::PROFILE_DESCRIPTION));
        
        return [
            'profile'  => $profile,
            'licenses' => $licenses,
        ];
    }
    
    protected function formInputsBuilders() {
        return [
            InputIntegerBuilder::init(ProfilesManager::COLUMN_ID)
                               ->build(),
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_NAME)
                                    ->setSpecialChar(TRUE)
                                    ->build(),
            InputAlphanumericBuilder::init(ProfilesManager::PROFILE_DESCRIPTION)
                                    ->setRequire(FALSE)
                                    ->build(),
            InputListIntegerBuilder::init(ProfilesLicensesManager::LICENSE_ID)
                                   ->build(),
        ];
    }
    
}
