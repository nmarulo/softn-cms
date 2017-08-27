<?php
/**
 * License.php
 */

namespace SoftnCMS\models\licenses;

use SoftnCMS\models\managers\OptionsLicensesManager;
use SoftnCMS\models\tables\OptionLicense;
use SoftnCMS\route\Route;
use SoftnCMS\util\Arrays;

/**
 * Class License
 * @author Nicolás Marulanda P.
 */
abstract class License {
    
    /** @var License */
    private static $INSTANCE;
    
    protected      $nameMethods;
    
    protected      $nameTable;
    
    /**
     * @var array Nombre de las columnas de la tabla.
     * Key(nombre de la constante en la clase XManager)
     * Value(nombre de la columna en base de datos)
     */
    protected $columns;
    
    /** @var Route */
    protected $route;
    
    /** @var array */
    protected $licenses;
    
    protected $userId;
    
    private   $nameSpaceAdmin;
    
    /**
     * License constructor.
     *
     * @param Route  $route
     * @param string $managerClass
     */
    public function __construct($route, $managerClass, $userId) {
        $this->nameSpaceAdmin = NAMESPACE_CONTROLLERS . Route::CONTROLLER_DIRECTORY_NAME_ADMIN . '\\';
        $this->route          = $route;
        $this->userId         = $userId;
        $this->licenses       = [];
        $this->setTableAndColumns($managerClass);
        $this->setMethods();
    }
    
    protected function setTableAndColumns($class) {
        $this->setTable($class);
        $reflectionClass = new \ReflectionClass($class);
        $this->columns   = $reflectionClass->getConstants();
        $reflectionClass = new \ReflectionClass(NAMESPACE_MODELS . 'CRUDManagerAbstract');
        $keys            = array_keys($reflectionClass->getConstants());
        $this->columns   = array_diff_key($this->columns, $keys);
    }
    
    private function setTable($class) {
        $const = "$class::TABLE";
        
        if (!defined($const)) {
            throw new \Exception("No existe la constante 'TABLE' en la clase $class.");
        }
        
        $this->nameTable = constant($const);
    }
    
    private function setMethods() {
        $controller          = $this->route->getControllerName() . 'Controller';
        $controllerNameSpace = $this->nameSpaceAdmin . $controller;
        $reflectionClass     = new \ReflectionClass($controllerNameSpace);
        $reflectionMethods   = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        $this->nameMethods = array_map(function($methodRefection) {
            return Arrays::get($methodRefection, 'name');
        }, $reflectionMethods);
    }
    
    /**
     * @return License
     */
    public static function getInstance() {
        return self::$INSTANCE;
    }
    
    /**
     * @param Route $route
     * @param int   $userId
     *
     * @return bool True, si tiene algún permiso mayor o igual que 'LICENSE_READ_CODE'
     * o True en caso de no encontrar la clase 'xLicense' correspondiente.
     */
    public static function initCheck($route, $userId) {
        $controllerName   = $route->getControllerName();
        $nameSpaceLicense = NAMESPACES_LICENSES . $controllerName . 'License';
        
        if (class_exists($nameSpaceLicense)) {
            self::$INSTANCE = new $nameSpaceLicense($route, $userId);
            
            return self::$INSTANCE->check();
        }
        
        return TRUE;
    }
    
    public function check() {
        $optionsLicensesManager = new OptionsLicensesManager();
        $this->licenses         = $optionsLicensesManager->searchAll($this->route->getControllerName(), $this->route->getMethodName(), $this->userId);
        
        return !empty($this->licenses);
    }
}
