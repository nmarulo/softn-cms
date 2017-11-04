<?php
/**
 * OptionLicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\classes\constants\Constants;
use SoftnCMS\models\managers\LicensesManager;
use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\tables\License;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\route\Route;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\controller\ControllerAbstract;
use SoftnCMS\util\form\builders\InputBooleanBuilder;
use SoftnCMS\util\form\builders\InputIntegerBuilder;
use SoftnCMS\util\Messages;
use SoftnCMS\util\Token;
use SoftnCMS\util\Util;

/**
 * Class OptionLicenseController
 * @author Nicolás Marulanda P.
 */
class OptionLicenseController extends ControllerAbstract {
    
    /** @var array */
    private $inputNames;
    
    public function index() {
        $licensesManager = new LicensesManager();
        $count           = $licensesManager->configuredCount();
        
        $this->sendDataView([
            'licenses' => $licensesManager->searchAllConfigured($this->rowsPages($count)),
        ]);
        $this->view();
    }
    
    public function create() {
        $licensesManager = new LicensesManager();
        $licenses        = $licensesManager->searchAllWithoutConfigured();
        
        if (empty($licenses)) {
            Messages::addWarning(__('No hay permisos para configurar.'), TRUE);
            $this->redirectToAction('index');
        }
        
        if ($this->checkSubmit(Constants::FORM_CREATE)) {
            if ($this->isValidForm()) {
                $optionsLicenseManager = new OptionsLicensesManager();
                $optionsLicenses       = $this->getForm('optionsLicenses');
                $len                   = count($optionsLicenses);
                $notError              = TRUE;
                
                for ($i = 0; $i < $len && $notError; ++$i) {
                    $notError = $optionsLicenseManager->create(Arrays::get($optionsLicenses, $i));
                }
                
                if ($notError) {
                    Messages::addSuccess(__('Permiso configurado correctamente.'), TRUE);
                    $this->redirectToAction('index');
                }
            }
            
            Messages::addDanger(__('Error al crear la configuración del permiso.'));
        }
        
        $this->sendDataView([
            'isUpdate'        => FALSE,
            'license'         => new License(),
            'licenses'        => $licenses,
            'optionsLicenses' => [],
            'dataList'        => $this->getViewData(),
            'title'           => __('Nueva configuración de permisos'),
        ]);
        $this->view('form');
    }
    
    private function getViewData() {
        $nameSpaceControllerAdmin = NAMESPACE_CONTROLLERS . Route::CONTROLLER_DIRECTORY_NAME_ADMIN . '\\';
        $licensesFiles            = Util::getFilesAndDirectories(LICENSES);
        
        $dataList = array_map(function($licenseFileName) use ($nameSpaceControllerAdmin) {
            $licenseClassName    = Util::removeExtension($licenseFileName);
            $className           = substr($licenseClassName, 0, strrpos($licenseClassName, 'License'));
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
        $reflectionManagerAbstractClass     = new \ReflectionClass('SoftnCMS\util\database\ManagerAbstract');
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
        $reflectionMethods = array_filter($reflectionMethods, function(\ReflectionMethod $reflectionMethod) {
            return $reflectionMethod->name != '__construct';
        });
        
        return array_map(function(\ReflectionMethod $reflectionMethod) {
            return strtoupper($reflectionMethod->name);
        }, $reflectionMethods);
    }
    
    public function update($id) {
        /*
         * NOTA: el parámetro ID sera el identificador del permisos.
         * Esto se debe, por que, las pagina de configurar de los permisos
         * se manejan todas las paginas al vez, en este caso no me sirve el ID de la tabla
         * ya que el ID del permisos es único dato común para esa configuración.
         */
        
        $optionsLicensesManager = new OptionsLicensesManager();
        $optionsLicenses        = $optionsLicensesManager->searchAllByLicenseId($id);
        
        if (empty($optionsLicenses)) {
            Messages::addDanger(__('La configuración del permiso no existe.'), TRUE);
            Util::redirect(Router::getSiteURL() . 'admin/optionlicense');
        } elseif ($this->checkSubmit(Constants::FORM_UPDATE)) {
            if ($this->isValidForm()) {
                $optionsLicenses = $this->getForm('optionsLicenses');
                $len             = count($optionsLicenses);
                $notError        = TRUE;
                
                for ($i = 0; $i < $len && $notError; ++$i) {
                    $optionLicense = Arrays::get($optionsLicenses, $i);
                    
                    if (empty($optionLicense) || empty($optionLicense->getId())) {
                        $notError = $optionsLicensesManager->create($optionLicense);
                    } elseif (empty($optionLicense->getOptionLicenseFieldsName())) {
                        $notError = $optionsLicensesManager->deleteById($optionLicense->getId());
                    } else {
                        $notError = $optionsLicensesManager->updateByColumnId($optionLicense);
                    }
                }
                
                if ($notError) {
                    //En caso de que el Id del permiso cambie.
                    $id              = $this->getForm('licenseId');
                    $optionsLicenses = $optionsLicensesManager->searchAllByLicenseId($id);
                    
                    if (empty($optionsLicenses)) {
                        Messages::addWarning(__('La configuración del permiso fue borrada al no tener asignado ningún campo.'), TRUE);
                        $this->redirectToAction('index');
                    }
                    
                    Messages::addSuccess(__('Configuración del permiso actualizado correctamente.'), TRUE);
                    $this->redirectToAction("update/$id");
                } else {
                    Messages::addDanger(__('Error al actualizar la configuración del permiso.'));
                }
            } else {
                Messages::addDanger(__('Error en los campos de la configuración del permiso.'));
            }
        }
        
        $optionsLicensesList = [];
        array_walk($optionsLicenses, function(OptionLicense $optionLicense) use (&$optionsLicensesList) {
            $methodName                                        = $optionLicense->getOptionLicenseMethodName();
            $controllerName                                    = $optionLicense->getOptionLicenseControllerName();
            $optionsLicensesList[$controllerName][$methodName] = [
                'fields' => $optionLicense->getOptionLicenseFieldsName(),
                'object' => $optionLicense,
            ];
            $optionsLicensesList[$controllerName]['insert']    = $optionLicense->getOptionLicenseCanInsert();
            $optionsLicensesList[$controllerName]['update']    = $optionLicense->getOptionLicenseCanUpdate();
            $optionsLicensesList[$controllerName]['delete']    = $optionLicense->getOptionLicenseCanDelete();
        });
        
        $licensesManager = new LicensesManager();
        $this->sendDataView([
            'isUpdate'        => TRUE,
            'optionsLicenses' => $optionsLicensesList,
            'license'         => $licensesManager->searchById($id),
            'licenses'        => $licensesManager->searchAllWithoutConfigured(),
            'dataList'        => $this->getViewData(),
            'title'           => __('Actualizar configuración del permiso'),
        ]);
        $this->view('form');
    }
    
    public function delete($id) {
        if (Token::check()) {
            $optionsLicenses = new OptionsLicensesManager();
            $result          = $optionsLicenses->deleteByLicenseId($id);
            $rowCount        = $optionsLicenses->getRowCount();
            
            if ($rowCount === 0) {
                Messages::addWarning(__('La configuración del permiso no existe.'), TRUE);
            } elseif ($result) {
                Messages::addSuccess(__('Configuración del permiso borrado correctamente.'), TRUE);
            } else {
                Messages::addDanger(__('Error al borrar la configuración del permiso.'), TRUE);
            }
        }
        
        $this->redirectToAction('index');
    }
    
    protected function formToObject() {
        $licenseId = $this->getInput(OptionsLicensesManager::LICENSE_ID);
        
        /* EJ:
         * $this->inputs =>
             * CATEGORY_UPDATE
             * CATEGORY_INSERT
             * CATEGORY_DELETE
             * CATEGORY_INDEX_category_name
             * CATEGORY_INDEX_category_description
         */
        $optionsLicenses  = [];
        $filterInputsKeys = array_keys($this->inputs);
        
        array_walk($this->inputNames, function($inputName) use ($filterInputsKeys, $licenseId, &$optionsLicenses) {
            $pageName   = Arrays::get($inputName, 'pageName');
            $capacities = Arrays::get($inputName, 'capacities');
            $capacities = array_filter($capacities, function($capacity) use ($filterInputsKeys) {
                return array_search($capacity, $filterInputsKeys, TRUE) !== FALSE;
            });
            $capacities = array_map(function($capacity) use ($pageName) {
                return strtolower(str_replace($pageName . '_', '', $capacity));
            }, $capacities);
            
            $methods = Arrays::get($inputName, 'methods');
            $methods = array_filter($methods, function($method) use ($filterInputsKeys, $pageName) {
                $methodName = Arrays::get($method, 'methodName');
                $fields     = Arrays::get($method, 'fields');
                $filter     = array_filter($fields, function($field) use ($filterInputsKeys) {
                    return array_search($field, $filterInputsKeys) !== FALSE;
                });
                $id         = $this->getInput(sprintf('%1$s_%2$s_id', $pageName, $methodName));
                
                //Si el ID esta vació, se esta creando una nueva configuración del permiso.
                return !empty($filter) || !empty($id);
            });
            $methods = array_map(function($method) use ($pageName, $filterInputsKeys) {
                $methodName       = Arrays::get($method, 'methodName');
                $fields           = Arrays::get($method, 'fields');
                $fields           = array_filter($fields, function($field) use ($filterInputsKeys) {
                    return array_search($field, $filterInputsKeys) !== FALSE;
                });
                $fields           = array_map(function($field) use ($pageName, $methodName) {
                    return str_replace(sprintf('%1$s_%2$s_', $pageName, $methodName), '', $field);
                }, $fields);
                $method['fields'] = $fields;
                
                return $method;
            }, $methods);
            
            array_walk($methods, function($method) use ($pageName, $capacities, $licenseId, &$optionsLicenses) {
                $methodName    = Arrays::get($method, 'methodName');
                $fields        = Arrays::get($method, 'fields');
                $id            = $this->getInput(sprintf('%1$s_%2$s_id', $pageName, $methodName));
                $optionLicense = new OptionLicense();
                $optionLicense->setId($id);
                $optionLicense->setOptionLicenseControllerName($pageName);
                $optionLicense->setOptionLicenseMethodName($methodName);
                $optionLicense->setOptionLicenseCanInsert(array_search('insert', $capacities) !== FALSE);
                $optionLicense->setOptionLicenseCanUpdate(array_search('update', $capacities) !== FALSE);
                $optionLicense->setOptionLicenseCanDelete(array_search('delete', $capacities) !== FALSE);
                $optionLicense->setOptionLicenseFieldsName($fields);
                $optionLicense->setLicenseId($licenseId);
                $optionsLicenses[] = $optionLicense;
            });
        });
        
        return [
            'optionsLicenses' => $optionsLicenses,
            'licenseId'       => $licenseId,
        ];
    }
    
    protected function formInputsBuilders() {
        $this->inputNames = $this->getInputNames();
        $output           = [];
        array_walk($this->inputNames, function($inputName) use (&$output) {
            $this->setCapacities($inputName, $output);
            $this->setFields($inputName, $output);
        });
        $output[] = InputIntegerBuilder::init(OptionsLicensesManager::LICENSE_ID)
                                       ->build();
        
        return $output;
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
    
    private function setCapacities($inputName, &$output) {
        $capacities = Arrays::get($inputName, 'capacities');
        
        if (!empty($capacities)) {
            array_walk($capacities, function($capacity) use (&$output) {
                if (Arrays::keyExists($_POST, $capacity)) {
                    $output[] = InputBooleanBuilder::init($capacity)
                                                   ->build();
                }
            });
        }
    }
    
    private function setFields($inputName, &$output) {
        $methods  = Arrays::get($inputName, 'methods');
        $pageName = Arrays::get($inputName, 'pageName');
        
        if (!empty($methods)) {
            array_walk($methods, function($method) use ($pageName, &$output) {
                $methodName = Arrays::get($method, 'methodName');
                $fields     = Arrays::get($method, 'fields');
                
                if (!empty($fields)) {
                    $addInputPageMethodId = FALSE;
                    array_walk($fields, function($field) use ($pageName, $methodName, &$addInputPageMethodId, &$output) {
                        if (Arrays::keyExists($_POST, $field)) {
                            $output[] = InputBooleanBuilder::init($field)
                                                           ->build();
                        }
                    });
                    
                    $output[] = InputIntegerBuilder::init(sprintf('%1$s_%2$s_id', $pageName, $methodName))
                                                   ->build();
                }
            });
        }
    }
    
}
