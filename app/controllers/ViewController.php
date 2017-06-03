<?php
/**
 * ViewController.php
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\util\Arrays;

/**
 * Class ViewController
 * @author Nicolás Marulanda P.
 */
class ViewController {
    
    /** @var string Nombre del directorio de la vista del controlador. */
    private static $DIRECTORY = '';
    
    /** @var array Lista de datos a enviar a la vista. */
    private static $VIEW_DATA = [];
    
    /** @var string Contenido principal de la vista. */
    private static $VIEW_CONTENT = '';
    
    /** @var array Lista de nombre de los scripts. */
    private static $VIEW_SCRIPTS = [];
    
    /** @var array Lista de nombre de los estilos. */
    private static $VIEW_STYLES = [];
    
    /**
     * Método que establece el nombre del directorio de la vista del controlador.
     *
     * @param string $directory
     */
    public static function setDirectory($directory) {
        self::$DIRECTORY = $directory;
    }
    
    /**
     * Método que incluye la vista completa.
     *
     * @param string $fileName Nombre de la vista.
     */
    public static function view($fileName) {
        self::setViewContent($fileName);
        self::includeView(VIEWS . 'index.php');
        self::$VIEW_DATA = [];
    }
    
    /**
     * Método que establece el contenido principal de la vista.
     *
     * @param string $fileName
     */
    private static function setViewContent($fileName) {
        self::$VIEW_CONTENT = VIEWS . self::$DIRECTORY . DIRECTORY_SEPARATOR . $fileName . '.php';
    }
    
    /**
     * Método que incluye la ruta.
     *
     * @param string $path Ruta del fichero.
     */
    private static function includeView($path) {
        require $path;
    }
    
    /**
     * Método que incluye únicamente la vista indicada del directorio actual.
     *
     * @param $fileName
     */
    public static function singleView($fileName) {
        self::singleViewDirectory($fileName, self::$DIRECTORY);
    }
    
    /**
     * Método que incluye únicamente la vista indicada,
     * con la posibilidad de indicar un directorio diferente.
     *
     * @param string $fileName
     * @param string $directory
     */
    public static function singleViewDirectory($fileName, $directory = '') {
        if (!empty($directory)) {
            $directory .= DIRECTORY_SEPARATOR;
        }
        
        self::includeView(VIEWS . $directory . $fileName . '.php');
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
        self::includeView(VIEWS . 'header.php');
    }
    
    /**
     * Método que incluye el pie de pagina común de la vista.
     */
    public static function footer() {
        self::includeView(VIEWS . 'footer.php');
    }
    
    /**
     * Método que incluye la barra lateral común de la vista.
     */
    public static function sidebar() {
        self::includeView(VIEWS . 'sidebar.php');
    }
    
    /**
     * Método que incluye el contenido de la vista.
     */
    public static function content() {
        self::includeView(self::$VIEW_CONTENT);
    }
    
    /**
     * Método que obtiene los datos enviados a la vista.
     *
     * @param int|string $key
     *
     * @return bool|mixed
     */
    public static function getViewData($key) {
        //TODO: Mostrar un mensaje si el indice no existe.
        return Arrays::get(self::$VIEW_DATA, $key);
    }
    
    /**
     * Método que incluye el nombre del script js.
     */
    public static function includeScripts() {
        $optionsManager = new OptionsManager();
        $baseUrl        = $optionsManager->getSiteUrl();
        
        foreach (self::$VIEW_SCRIPTS as $script) {
            $script = $baseUrl . $script;
            echo "<script src='$script.js' type='text/javascript'></script>";
        }
    }
    
    public static function includeStyles() {
        $optionsManager = new OptionsManager();
        $baseUrl        = $optionsManager->getSiteUrl();
        
        foreach (self::$VIEW_STYLES as $style) {
            $style = $baseUrl . $style;
            echo "<link href='$style.css' rel='stylesheet' type='text/css'/>";
        }
    }
    
    public static function registerScript($scriptName) {
        self::registerScriptRoute("app/resources/js/$scriptName");
    }
    
    public static function registerScriptRoute($scriptRute) {
        if (Arrays::valueExists(self::$VIEW_SCRIPTS, $scriptRute) === FALSE) {
            self::$VIEW_SCRIPTS[] = $scriptRute;
        }
    }
    
    public static function registerStyle($styleName) {
        self::registerStyleRoute("app/resources/css/$styleName");
    }
    
    public static function registerStyleRoute($styleRute) {
        if (Arrays::valueExists(self::$VIEW_STYLES, $styleRute) === FALSE) {
            self::$VIEW_STYLES[] = $styleRute;
        }
    }
}
