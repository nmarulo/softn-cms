<?php
/**
 * OptionLicenseController.php
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\controllers\CUDControllerAbstract;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\LicensesProfilesManager;
use SoftnCMS\models\managers\PostsCategoriesManager;
use SoftnCMS\models\managers\PostsTermsManager;
use SoftnCMS\models\managers\UsersProfilesManager;
use SoftnCMS\route\Route;
use SoftnCMS\util\form\Form;
use SoftnCMS\util\Util;

/**
 * Class OptionLicenseController
 * @author Nicolás Marulanda P.
 */
class OptionLicenseController extends CUDControllerAbstract {
    
    public function create() {
        // TODO: Implement create() method.
    }
    
    public function update($id) {
        // TODO: Implement update() method.
    }
    
    public function delete($id) {
        parent::delete($id);
    }
    
    protected function form() {
        return [];
    }
    
    protected function filterInputs() {
        return Form::inputFilter();
    }
    
    protected function read() {
        ViewController::sendViewData('managers', $this->getManagers());
        ViewController::sendViewData('controllers', $this->getControllers());
    }
    
    private function getManagers() {
        $reflectionClass   = new \ReflectionClass(NAMESPACE_MODELS . 'CRUDManagerAbstract');
        $constantsIgnore   = array_keys($reflectionClass->getConstants());
        $constantsIgnore[] = 'MENU_SUB_PARENT';
        $tablesIgnore      = [
            PostsTermsManager::TABLE,
            PostsCategoriesManager::TABLE,
            LicensesProfilesManager::TABLE,
            UsersProfilesManager::TABLE,
        ];
        $managerFiles      = Util::getFilesAndDirectories(MANAGERS);
        
        $managers = array_map(function($managerFile) use ($constantsIgnore, $tablesIgnore) {
            $manager          = Util::removeExtension($managerFile);
            $managerNameSpace = NAMESPACE_MANAGERS . $manager;
            $reflectionClass  = new \ReflectionClass($managerNameSpace);
            
            if ($this->checkConstantTable($managerNameSpace, $tablesIgnore)) {
                return "";
            }
            
            $constants    = $reflectionClass->getConstants();
            $constantKeys = array_keys($constants);
            array_walk($constantKeys, function($constantKey) use ($constantsIgnore, &$constants) {
                if (array_search($constantKey, $constantsIgnore) !== FALSE) {
                    unset($constants[$constantKey]);
                }
            });
            
            return [
                'constants' => $constants,
            ];
        }, $managerFiles);
        
        //array_filter elimina los valores vacíos.
        return array_merge(array_filter($managers));
    }
    
    private function checkConstantTable($classNameSpaces, $tablesIgnore) {
        $classNameSpaces .= '::TABLE';
        $constantDefined = defined($classNameSpaces);
        
        return !$constantDefined || ($constantDefined && array_search(constant($classNameSpaces), $tablesIgnore) !== FALSE);
    }
    
    private function getControllers() {
        $directoryAdmin   = CONTROLLERS . Route::CONTROLLER_DIRECTORY_NAME_ADMIN;
        $nameSpaceAdmin   = NAMESPACE_CONTROLLERS . Route::CONTROLLER_DIRECTORY_NAME_ADMIN . '\\';
        $controllersFiles = Util::getFilesAndDirectories($directoryAdmin);
        
        $controllers = array_map(function($controllerFile) use ($nameSpaceAdmin) {
            $controller          = Util::removeExtension($controllerFile);
            $controllerNameSpace = $nameSpaceAdmin . $controller;
            $classMethods        = get_class_methods($controllerNameSpace);
            
            return [
                'controllerName'      => str_replace('Controller', '', $controller),
                'controllerNameSpace' => $controllerNameSpace,
                'controllerMethods'   => array_filter($classMethods, function($method) {
                    //Filtrar métodos
                    return $method != 'reloadAJAX' && $method != 'pagination';
                }),
            ];
        }, $controllersFiles);
        
        return array_merge($controllers);
    }
    
}
