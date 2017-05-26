<?php

/**
 * Modulo controlador: Presenta los módulos vista correspondientes.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\helpers\Helps;
use SoftnCMS\models\admin\Option;
use SoftnCMS\models\admin\Options;
use SoftnCMS\models\admin\OptionUpdate;

/**
 * Clase CategoryController presenta los módulos vista correspondientes.
 * @author Nicolás Marulanda P.
 */
class ViewController {
    
    /** @var Request Instancia Request. */
    private $request;
    
    /** @var array Datos enviados al modulo. */
    private $data;
    
    /** @var string Ruta del modulo vista. */
    private $nameView;
    
    /** @var string Nombre de la ruta correspondiente al método con las vistas. */
    private $nameMethodViews;
    
    /** @var string Nombre del tema actual */
    private $nameTheme;
    
    /**
     * Constructor.
     *
     * @param Request $request Instancia Request
     * @param array   $data    Datos enviados al modulo.
     */
    public function __construct(Request $request, $data) {
        $this->request         = $request;
        $this->data            = $data;
        $this->nameTheme       = '';
        $this->nameMethodViews = '';
        $this->methodViews();
        $this->getNameView();
    }
    
    /**
     * Método que establece el nombre del método con las vistas correspondientes.
     */
    private function methodViews() {
        $this->nameMethodViews = $this->getKeyRouter();
        
        if ($this->nameMethodViews == 'default') {
            $this->nameMethodViews = 'theme';
        }
    }
    
    /**
     * Método que obtiene el indice de la función "Router::getRoutes()"
     * de la pagina actual.
     * @return string
     */
    private function getKeyRouter() {
        $controller = strtolower($this->request->getController());
        
        //Obtiene el indice de la ruta.
        //Comprueba si el nombre controlador es una rutas
        $key = array_search($controller, Router::getRoutes(), TRUE);
        if (empty($key)) {
            //de lo contrario comprueba el nombre de la ruta según REQUEST
            $key = array_search($this->request->getRoute(), Router::getRoutes(), TRUE);
        }
        
        return $key;
    }
    
    /**
     * Método que establece el nombre del modelo vista a incluir
     * según el método enviado por url.
     */
    private function getNameView() {
        switch ($this->request->getMethod()) {
            case 'delete':
            case 'index':
                $this->nameView = \strtolower($this->request->getController());
                break;
            case 'insert':
            case 'update':
                $this->nameView = \strtolower($this->request->getController()) . 'Form';
                break;
        }
    }
    
    /**
     * Método que muestra los módulos vista al usuario.
     */
    public function render() {
        $key = $this->getKeyRouter();
        //Obtiene el directorio correspondiente.
        $view = Router::getViewPath()[Router::getVIEWS()[$key]];
        //Si el indice de la ruta es "default" obtiene el nombre del tema actual.
        if ($key == 'default') {
            $optionTheme = Option::selectByName('optionTheme');
            $this->nameTheme = $optionTheme->getOptionValue();
            $this->checkTheme($this->nameTheme, $optionTheme);
            $view .= $this->nameTheme . DIRECTORY_SEPARATOR;
        }
        
        $view .= $this->nameView . '.php';
        
        //En caso de error.
        if (!\is_readable($view)) {
            Helps::redirect();
        }
        
        //Se obtiene los datos enviados a la vista.
        if (\is_array($this->data)) {
            \extract($this->data, EXTR_PREFIX_INVALID, 'softn');
        }
        
        //Array con la ruta de los módulos vista a incluir.
        $viewsRequire = \call_user_func([
            $this,
            $this->nameMethodViews,
        ], $view);
        
        foreach ($viewsRequire as $value) {
            require $value;
        }
    }
    
    /**
     * Método que obtiene los módulos vista del tema de la aplicación.
     *
     * @param string $view Ruta de modulo vista a incluir.
     *
     * @return array
     */
    private function theme($view) {
        $path = \THEMES . $this->nameTheme . \DIRECTORY_SEPARATOR;
        
        return [
            $path . 'header.php',
            $view,
            $path . 'footer.php',
        ];
    }
    
    private function checkTheme($name, $optionTheme){
        $error = FALSE;
        $themeRoute = THEMES . $name . DIRECTORY_SEPARATOR . 'index.php';
        
        if(!file_exists($themeRoute)){
            $error = TRUE;
            $themeRoute = THEMES . 'default' . DIRECTORY_SEPARATOR . 'index.php';
            
            if(file_exists($themeRoute)){
                $option = new OptionUpdate($optionTheme, 'default');
                $option->update();
                $error = FALSE;
            }
        }
        
        if($error){
            Messages::addError('No se encontró ninguna plantilla disponible.');
            Helps::redirect(Router::getRoutes()['login']);
        }
    }
    
    /**
     * Método que obtiene los módulos vista del panel de administración.
     *
     * @param string $view Ruta de modulo vista a incluir.
     *
     * @return array
     */
    private function admin($view) {
        return [
            \VIEWS_ADMIN . 'header.php',
            \VIEWS . 'messages.php',
            \VIEWS_ADMIN . 'topbar.php',
            \VIEWS_ADMIN . 'leftbar.php',
            $view,
            \VIEWS_ADMIN . 'footer.php',
        ];
    }
    
    /**
     * Método que obtiene los módulos vista de la pagina de registro.
     *
     * @param string $view Ruta de modulo vista a incluir.
     *
     * @return array
     */
    private function register($view) {
        return $this->login($view);
    }
    
    /**
     * Método que obtiene los módulos vista de la pagina de login.
     *
     * @param string $view Ruta de modulo vista a incluir.
     *
     * @return array
     */
    private function login($view) {
        return [
            \VIEWS . 'headerLogin.php',
            \VIEWS . 'messages.php',
            $view,
            \VIEWS . 'footerLogin.php',
        ];
    }
    
    /**
     * Método que obtiene los módulos vista de la pagina de instalación.
     *
     * @param $view
     *
     * @return array
     */
    private function install($view) {
        return [
            //            \VIEWS_ADMIN . 'header.php',
            \VIEWS . 'messages.php',
            $view,
            //            \VIEWS_ADMIN . 'footer.php',
        ];
    }
    
}
