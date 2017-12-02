<?php
/**
 * LicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\ProfilesLicensesManager;
use SoftnCMS\models\managers\ProfilesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\models\tables\ProfileLicense;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputAlphanumericBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\builders\InputListIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;

/**
 * Class LicenseController
 * @author Nicolás Marulanda P.
 */
class LicenseController extends ControllerAbstract {
    
    public function create() {
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $licensesManager = new LicensesManager($this->getConnectionDB());
                $license         = $this->getForm('license');
                
                if ($licensesManager->create($license)) {
                    $profiles = $this->getForm('profiles');
                    $this->createOrDeleteProfiles($profiles, $licensesManager->getLastInsertId());
                    Messages::addSuccess(__('Permiso creado correctamente.'));
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al crear el permiso.'));
        }
        
        $this->sendViewProfiles();
        $this->sendDataView([
            'isUpdate' => FALSE,
            'license'  => new License(),
            'title'    => __('Crear nuevo permiso'),
        ]);
        $this->view('form');
    }
    
    private function createOrDeleteProfiles($profilesId, $licenseId) {
        $profilesLicensesManager = new ProfilesLicensesManager($this->getConnectionDB());
        
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
                if ($profilesLicensesManager->create($profileLicense) === FALSE) {
                    ++$numError;
                }
            });
            
            if ($numError > 0) {
                Messages::addDanger(__('Error al actualizar los perfiles.'));
            }
        }
    }
    
    private function getProfilesIdByLicenseId($licenseId) {
        $licensesProfilesManager = new ProfilesLicensesManager($this->getConnectionDB());
        $licenseProfiles         = $licensesProfilesManager->searchAllByLicenseId($licenseId);
        
        return array_map(function(ProfileLicense $profileLicense) {
            return $profileLicense->getProfileId();
        }, $licenseProfiles);
    }
    
    private function sendViewProfiles() {
        $profilesManager = new ProfilesManager($this->getConnectionDB());
        $this->sendDataView(['profiles' => $profilesManager->searchAll()]);
    }
    
    public function update($id) {
        $licensesManager = new LicensesManager($this->getConnectionDB());
        $license         = $licensesManager->searchById($id);
        
        if (empty($license)) {
            Messages::addDanger(__('El permiso no existe.'), TRUE);
            $this->redirectToAction('index');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $license = $this->getForm('license');
                
                if ($licensesManager->update($license)) {
                    $license  = $licensesManager->searchById($id);
                    $profiles = $this->getForm('profiles');
                    $this->createOrDeleteProfiles($profiles, $id);
                    Messages::addSuccess(__('Permiso actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar el permiso.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos del permiso.'));
            }
        }
        
        $this->sendViewProfiles();
        $this->sendDataView([
            'isUpdate'           => TRUE,
            'selectedProfilesId' => $this->getProfilesIdByLicenseId($id),
            'license'            => $license,
            'title'              => __('Actualizar permiso'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $licensesManager = new LicensesManager($this->getConnectionDB());
            $result          = $licensesManager->deleteById($id);
            $rowCount        = $licensesManager->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('El permiso no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Permiso borrado correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar el permiso.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    public function index() {
        $licensesManager = new LicensesManager($this->getConnectionDB());
        $count           = $licensesManager->count();
        
        $this->sendDataView([
            'licenses' => $licensesManager->searchAll($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    protected function formToObject() {
        $license  = new License();
        $profiles = $this->getInput(ProfilesLicensesManager::PROFILE_ID);
        $license->setId($this->getInput(LicensesManager::COLUMN_ID));
        $license->setLicenseName($this->getInput(LicensesManager::LICENSE_NAME));
        $license->setLicenseDescription($this->getInput(LicensesManager::LICENSE_DESCRIPTION));
        
        return [
            'license'  => $license,
            'profiles' => $profiles,
        ];
    }
    
    protected function formInputsBuilders() {
        return [
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
        ];
        
    }
}
