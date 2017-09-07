<?php
/**
 * OptionLicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\CRUDManagerAbstract;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\MethodLicense;
use SoftnCMS\models\PageLicense;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\route\Route;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Util;

/**
 * Class OptionLicenseController
 * @author Nicolás Marulanda P.
 */
class OptionLicenseController extends CUDControllerAbstract {
    
    public function create() {
        if (Form::submit(CRUDManagerAbstract::FORM_CREATE)) {
            $optionsLicenseManager = new OptionsLicensesManager();
            $form                  = $this->form();
            
            if (!empty($form)) {
                $optionLicense = Arrays::get($form, 'optionLicense');
                
                if ($optionsLicenseManager->create($optionLicense)) {
                    Messages::addSuccess(__('Permiso configurado correctamente.', TRUE));
                    Util::redirect(Router::getSiteURL() . 'admin/optionlicense');
                }
            }
            
            Messages::addDanger(__('Error al crear la configuración del permiso.'));
        }
        
        $licensesManager = new LicensesManager();
        ViewController::sendViewData('licenses', $licensesManager->searchAllWithoutConfigured());
        ViewController::sendViewData('optionLicense', new OptionLicense());
        ViewController::sendViewData('dataList', $this->getViewData());
        ViewController::sendViewData('title', __('Nueva configuración de permisos'));
        ViewController::view('form');
    }
    
    protected function form() {
        $filterInputs = $this->filterInputs();
        
        if (empty($filterInputs)) {
            return FALSE;
        }
        
        $optionLicense = new OptionLicense();
        $optionLicense->setId(Arrays::get($filterInputs, OptionsLicensesManager::ID));
        $optionLicense->setLicenseId(Arrays::get($filterInputs, OptionsLicensesManager::LICENSE_ID));
        $optionLicense->setOptionLicenseObject($this->getOptionLicenseObject($filterInputs));
        
        return [
            'optionLicense' => $optionLicense,
        ];
    }
    
    protected function filterInputs() {
        $inputNames = $this->getInputNames();
        array_walk($inputNames, function($inputName) {
            $this->setCapacities($inputName);
            $this->setFields($inputName);
        });
        Form::addInput(InputIntegerBuilder::init(OptionsLicensesManager::LICENSE_ID)
                                          ->build());
        Form::addInput(InputIntegerBuilder::init(OptionsLicensesManager::ID)
                                          ->build());
        
        return Form::inputFilter();
    }
    
    private function getInputNames() {
        $dataList   = $this->getViewData();
        $inputNames = array_map(function($data) {
            $methods           = [];
            $className         = Arrays::get($data, 'className');
            $controllerMethods = Arrays::get($data, 'controllerMethods');
            $managerConstants  = array_values(Arrays::get($data, 'managerConstants'));
            
            array_walk($controllerMethods, function($methodName) use (&$methods, $className, $managerConstants) {
                $methods[] = [
                    'methodName' => $methodName,
                    'fields'     => array_map(function($columnName) use ($className, $methodName) {
                        return sprintf('%1$s_%2$s_%3$s', $className, $methodName, $columnName);
                    }, $managerConstants),
                ];
            });
            
            return [
                'pageName'   => $className,
                'capacities' => [
                    $className . '_UPDATE',
                    $className . '_INSERT',
                    $className . '_DELETE',
                ],
                'methods'    => $methods,
            ];
        }, $dataList);
        
        return $inputNames;
    }
    
    private function getViewData() {
        $nameSpaceControllerAdmin = NAMESPACE_CONTROLLERS . Route::CONTROLLER_DIRECTORY_NAME_ADMIN . '\\';
        $licensesFiles            = Util::getFilesAndDirectories(LICENSES);
        
        $dataList = array_map(function($licenseFileName) use ($nameSpaceControllerAdmin) {
            $licenseClassName    = Util::removeExtension($licenseFileName);
            $className           = str_replace('License', '', $licenseClassName);
            $nameSpaceManager    = call_user_func(NAMESPACES_LICENSES . $licenseClassName . '::getManagerClass');
            $nameSpaceController = $nameSpaceControllerAdmin . $className . 'Controller';
            $managerConstants    = $this->getManagerConstants($nameSpaceManager);
            
            return [
                'className'         => strtoupper($className),
                'controllerMethods' => $this->getControllerMethods($nameSpaceController),
                'managerConstants'  => Arrays::get($managerConstants, 'columnNames'),
            ];
        }, $licensesFiles);
        
        return array_merge($dataList);
    }
    
    private function getManagerConstants($nameSpaceManager) {
        $reflectionManagerAbstractClass     = new \ReflectionClass(NAMESPACE_MODELS . 'CRUDManagerAbstract');
        $reflectionManagerClass             = new \ReflectionClass($nameSpaceManager);
        $constantsIgnore                    = $reflectionManagerAbstractClass->getConstants();
        $managerConstants                   = $reflectionManagerClass->getConstants();
        $constantsIgnore['MENU_SUB_PARENT'] = 0;
        $constantsIgnore['TABLE']           = '';
        
        return [
            'columnNames' => array_diff_key($managerConstants, $constantsIgnore),
            'table'       => $managerConstants['TABLE'],
        ];
    }
    
    private function getControllerMethods($nameSpaceController) {
        $reflectionClass   = new \ReflectionClass($nameSpaceController);
        $reflectionMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        return array_map(function(\ReflectionMethod $reflectionMethod) {
            return strtoupper($reflectionMethod->name);
        }, $reflectionMethods);
    }
    
    private function setCapacities($inputName) {
        $capacities = Arrays::get($inputName, 'capacities');
        
        if (!empty($capacities)) {
            array_walk($capacities, function($capacity) {
                if (Arrays::keyExists($_POST, $capacity)) {
                    Form::addInput(InputBooleanBuilder::init($capacity)
                                                      ->build());
                }
            });
        }
    }
    
    private function setFields($inputName) {
        $methods = Arrays::get($inputName, 'methods');
        
        if (!empty($methods)) {
            array_walk($methods, function($method) {
                $fields = Arrays::get($method, 'fields');
                
                if (!empty($fields)) {
                    array_walk($fields, function($field) {
                        if (Arrays::keyExists($_POST, $field)) {
                            Form::addInput(InputBooleanBuilder::init($field)
                                                              ->build());
                        }
                    });
                }
            });
        }
    }
    
    private function getOptionLicenseObject($filterInputs) {
        $filterInputsKey = array_keys($filterInputs);
        $optionLicense   = [];
        $inputNames      = $this->getInputNames();
        
        array_walk($inputNames, function($inputName) use (&$optionLicense, $filterInputsKey) {
            $pageName       = Arrays::get($inputName, 'pageName');
            $methods        = Arrays::get($inputName, 'methods');
            $methodLicenses = [];
            
            array_walk($methods, function($method) use (&$methodLicenses, $pageName, $filterInputsKey) {
                $methodName = Arrays::get($method, 'methodName');
                $fields     = Arrays::get($method, 'fields');
                $fields     = array_filter($fields, function($field) use ($filterInputsKey) {
                    return array_search($field, $filterInputsKey, TRUE) !== FALSE;
                });
                
                if (!empty($fields)) {
                    $fields        = array_map(function($field) use ($pageName, $methodName) {
                        return str_replace(sprintf('%1$s_%2$s_', $pageName, $methodName), '', $field);
                    }, $fields);
                    $methodLicense = new MethodLicense($methodName, $fields);
                    
                    $methodLicenses[$methodLicense->getMethodName()] = $methodLicense;
                }
            });
            
            if (!empty($methodLicenses)) {
                $capacities  = Arrays::get($inputName, 'capacities');
                $pageLicense = new PageLicense($pageName, $methodLicenses);
                array_walk($capacities, function($capacity) use (&$pageLicense, $filterInputsKey, $pageName) {
                    if (array_search($capacity, $filterInputsKey) !== FALSE) {
                        $method     = str_replace($pageName . '_', '', $capacity);
                        $method     = ucfirst(strtolower($method));
                        $methodName = "setCan$method";
                        
                        if (method_exists($pageLicense, $methodName)) {
                            call_user_func([
                                $pageLicense,
                                "setCan$method",
                            ], TRUE);
                        }
                    }
                });
                $optionLicense[] = $pageLicense;
            }
        });
        
        return $optionLicense;
    }
    
    public function update($id) {
        $optionsLicensesManager = new OptionsLicensesManager();
        $licenseId              = Arrays::get($_GET, 'licenseId');
        $optionLicense          = $optionsLicensesManager->searchByIdAndLicenseId($id, $licenseId);
        
        if (empty($optionLicense)) {
            Messages::addDanger(__('La configuración del permiso no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/optionlicense');
        } elseif (Form::submit(CRUDManagerAbstract::FORM_UPDATE)) {
            $form = $this->form();
            
            if (empty($form)) {
                Messages::addDanger(__('Error en los campos de la configuración del permiso.'));
            } else {
                $optionLicense = Arrays::get($form, 'optionLicense');
                
                if ($optionsLicensesManager->update($optionLicense)) {
                    Messages::addSuccess(__('Configuración del permiso actualizado correctamente.'));
                } else {
                    Messages::addDanger(__('Error al actualizar la configuración del permiso.'));
                }
            }
        }
        
        $licensesManager = new LicensesManager();
        $license         = $licensesManager->searchById($licenseId);
        ViewController::sendViewData('optionLicense', $optionLicense);
        ViewController::sendViewData('license', $license);
        ViewController::sendViewData('licenses', $licensesManager->searchAllWithoutConfigured());
        ViewController::sendViewData('dataList', $this->getViewData());
        ViewController::sendViewData('title', __('Actualizar configuración del permiso'));
        ViewController::view('form');
    }
    
    public function delete($id) {
        $optionsLicenses = new OptionsLicensesManager();
        
        if (empty($optionsLicenses->delete($id))) {
            Messages::addDanger(__('Error al borrar la configuración del permiso.'));
        } else {
            Messages::addSuccess(__('Configuración del permiso borrado correctamente.'));
        }
        
        parent::delete($id);
    }
    
    protected function read() {
        $filters         = [];
        $optionsLicenses = new OptionsLicensesManager();
        $count           = $optionsLicenses->count();
        $pagination      = parent::pagination($count);
        
        if ($pagination !== FALSE) {
            $filters['limit'] = $pagination;
        }
        
        ViewController::sendViewData('optionLicenses', $optionsLicenses->read($filters));
    }
    
}
