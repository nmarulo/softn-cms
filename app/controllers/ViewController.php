<?php
/**
 * ViewController.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\route\Route;
use SoftnCMS\rute\Router;
use SoftnCMS\util\Arrays;
use SoftnCMS\util\Logger;

/**
 * Class ViewController
 * @author Nicolás Marulanda P.
 */
class ViewController {
    
    /** @var string Nombre del directorio del controlador de la vista. */
    private static $DIRECTORY_VIEWS_CONTROLLER = '';
    
    /** @var string Nombre del directorio de la vista o del tema actual. */
    private static $DIRECTORY_VIEWS = '';
    
    /** @var array Lista de datos a enviar a la vista. */
    private static $VIEW_DATA = [];
    
    /** @var string Ruta de la vista del controlador a incluir. */
    private static $CONTROLLER_VIEW_PATH = '';
    
    /** @var array Lista de nombre de los scripts. */
    private static $VIEW_SCRIPTS = [];
    
    /** @var array Lista de nombre de los estilos. */
    private static $VIEW_STYLES = [];
    
    /** @var string Ruta del directorio de las vistas. */
    private static $VIEW_PATH = '';
    
    /** @var \Closure */
    private static $VIEW_DATA_BASE_CLOSURE;
    
    /**
     * @param string $viewPath
     */
    public static function setViewPath($viewPath) {
        self::$VIEW_PATH = $viewPath;
    }
    
    /**
     * @param string $directoryViews
     */
    public static function setDirectoryViews($directoryViews) {
        self::$DIRECTORY_VIEWS = $directoryViews;
    }
    
    /**
     * Método que establece el nombre del directorio de la vista del controlador.
     *
     * @param string $directoryViewsController
     */
    public static function setDirectoryViewsController($directoryViewsController) {
        self::$DIRECTORY_VIEWS_CONTROLLER = $directoryViewsController;
    }
    
    /**
     * Método que incluye la vista completa.
     *
     * @param string $fileName Nombre de la vista.
     */
    public static function view($fileName) {
        self::callViewDataBase();
        self::$CONTROLLER_VIEW_PATH = self::getControllerViewPath($fileName);
        self::includePath(self::getPathView('index'));
        self::$VIEW_DATA = [];
    }
    
    private static function getControllerViewPath($fileName) {
        return self::getPath($fileName, self::$DIRECTORY_VIEWS, self::$DIRECTORY_VIEWS_CONTROLLER);
    }
    
    private static function getPath($fileName, $viewsDirectory, $directory) {
        $directory      = self::addDirectorySeparator($directory);
        $viewsDirectory = self::addDirectorySeparator($viewsDirectory);
        
        return self::$VIEW_PATH . $viewsDirectory . $directory . $fileName . '.php';
    }
    
    private static function addDirectorySeparator($value) {
        if (empty($value)) {
            return '';
        }
        
        return $value . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Método que incluye la ruta.
     *
     * @param string $path Ruta del fichero.
     */
    private static function includePath($path) {
        if (file_exists($path)) {
            require $path;
        } else {
            Logger::getInstance()
                  ->error('La ruta de la vista no existe.', ['path' => $path]);
        }
    }
    
    private static function getPathView($fileName) {
        return self::getPath($fileName, self::$DIRECTORY_VIEWS, '');
    }
    
    /**
     * Método que incluye únicamente la vista indicada del directorio actual.
     *
     * @param $fileName
     */
    public static function singleView($fileName) {
        self::singleViewByDirectory($fileName, self::$DIRECTORY_VIEWS, self::$DIRECTORY_VIEWS_CONTROLLER);
    }
    
    /**
     * Método que incluye únicamente la vista indicada,
     * con la posibilidad de indicar un directorio diferente.
     *
     * @param string $fileName
     * @param string $viewDirectory
     * @param string $directory
     */
    public static function singleViewByDirectory($fileName, $viewDirectory = '', $directory = '') {
        self::callViewDataBase();
        self::includePath(self::getPath($fileName, $viewDirectory, $directory));
    }
    
    public static function singleRootView($fileName) {
        self::singleViewByDirectory($fileName, self::$DIRECTORY_VIEWS, '');
    }
    
    /**
     * Método que establece los datos a enviar a la vista.
     *
     * @param string $key  Indice.
     * @param mixed  $data Datos.
     */
    public static function sendViewData($key, $data) {
        self::$VIEW_DATA[$key] = $data;
    }
    
    /**
     * Método que incluye el encabezado común de la vista.
     */
    public static function header() {
        self::includePath(self::getPathView('header'));
    }
    
    /**
     * Método que incluye el pie de pagina común de la vista.
     */
    public static function footer() {
        self::includePath(self::getPathView('footer'));
    }
    
    /**
     * Método que incluye la barra lateral común de la vista.
     */
    public static function sidebar() {
        self::includePath(self::getPathView('sidebar'));
    }
    
    /**
     * Método que incluye el contenido de la vista.
     */
    public static function content() {
        self::includePath(self::$CONTROLLER_VIEW_PATH);
    }
    
    /**
     * Método que obtiene los datos enviados a la vista.
     *
     * @param int|string $key
     *
     * @return bool|mixed
     */
    public static function getViewData($key) {
        if (!Arrays::keyExists(self::$VIEW_DATA, $key)) {
            Logger::getInstance()
                  ->debug('La variable enviada a la vista no existe', ['Clave' => $key]);
        }
        
        return Arrays::get(self::$VIEW_DATA, $key);
    }
    
    /**
     * Método que incluye el nombre del script js.
     */
    public static function includeScripts() {
        self::$VIEW_SCRIPTS = array_map(function($path) {
            return "<script src='$path.js' type='text/javascript'></script>";
        }, self::$VIEW_SCRIPTS);
        
        echo implode('', self::$VIEW_SCRIPTS);
    }
    
    public static function includeStyles() {
        self::$VIEW_STYLES = array_map(function($path) {
            return "<link href='$path.css' rel='stylesheet' type='text/css'/>";
        }, self::$VIEW_STYLES);
        
        echo implode('', self::$VIEW_STYLES);
    }
    
    public static function registerScript($scriptName) {
        self::registerScriptRoute(self::getPathResources('js', $scriptName));
    }
    
    public static function registerScriptRoute($scriptRute) {
        $scriptRute = Router::getSiteURL() . $scriptRute;
        
        if (Arrays::valueExists(self::$VIEW_SCRIPTS, $scriptRute) === FALSE) {
            self::$VIEW_SCRIPTS[] = $scriptRute;
        } else {
            Logger::getInstance()
                  ->debug('El script ya existe.', ['scriptRute' => $scriptRute]);
        }
    }
    
    private static function getPathResources($type, $fileName) {
        return sprintf('app%1$s/resources/%2$s/%3$s', self::getPathTheme(), $type, $fileName);
    }
    
    private static function getPathTheme() {
        if (Router::getCurrentDirectory() == Route::CONTROLLER_DIRECTORY_NAME_THEME) {
            //$DIRECTORY_VIEWS contiene el nombre del tema.
            return '/themes/' . self::$DIRECTORY_VIEWS;
        }
        
        return '';
    }
    
    public static function registerStyle($styleName) {
        self::registerStyleRoute(self::getPathResources('css', $styleName));
    }
    
    public static function registerStyleRoute($styleRute) {
        $styleRute = Router::getSiteURL() . $styleRute;
        
        if (Arrays::valueExists(self::$VIEW_STYLES, $styleRute) === FALSE) {
            self::$VIEW_STYLES[] = $styleRute;
        } else {
            Logger::getInstance()
                  ->debug('El css ya existe.', ['styleRute' => $styleRute]);
        }
    }
    
    /**
     * @param \Closure $closure
     */
    public static function setViewDataBase($closure) {
        self::$VIEW_DATA_BASE_CLOSURE = $closure;
    }
    
    private static function callViewDataBase() {
        if (is_callable(self::$VIEW_DATA_BASE_CLOSURE)) {
            $data = call_user_func(self::$VIEW_DATA_BASE_CLOSURE);
            
            if (is_array($data)) {
                self::$VIEW_DATA = array_merge($data, self::$VIEW_DATA);
            }
        }
    }
}
