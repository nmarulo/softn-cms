<?php

/**
 * Controlador del modulo vista.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\controllers\Request;

/**
 * Clase responsable de presentar el modulo vista al usuario.
 *
 * @author Nicolás Marulanda P.
 */
class ViewController {

    /** @var Request Instancia Request. */
    private $request;

    /** @var array Datos enviados al modulo. */
    private $data;

    /** @var string Ruta del modulo vista. */
    private $nameView;

    /**
     * La aplicación se divide en 3 zonas cuyas vistas no son comunes.
     * Zonas: "theme" Tema de la aplicación, "admin" Panel de administración 
     * y "login" Formulario de inicio de sesión y registro de usuario.
     * @var string Guarda el nombre de la zona a mostrar.
     */
    private $nameMethodViews;

    /**
     * Constructor.
     * @param Request $request Instancia Request
     * @param array $data Datos enviados al modulo.
     */
    public function __construct(Request $request, $data) {
        $this->request = $request;
        $this->data = $data;
        $this->methodViews();
        $this->getNameView();
    }

    /**
     * Metodo que muestra los modulos vista al usuario.
     */
    public function render() {
        $view = \VIEWS . $this->nameView . '.php';

        //En caso de error se muestra la vista index.
        if (!\is_readable($view)) {
            $this->nameView = 'index';
            $view = \VIEWS . $this->nameView . '.php';
        }

        //Se obtiene los datos enviados a la vista.
        if (\is_array($this->data)) {
            \extract($this->data, EXTR_PREFIX_INVALID, 'softn');
        }
        
        //Array con la ruta de los modulos vista a incluir.
        $viewsRequire = \call_user_func([$this, $this->nameMethodViews], $view);

        foreach ($viewsRequire as $value) {
            require $value;
        }
    }

    /**
     * Metodo que establece la zona en la que se encuentra el usuario.
     */
    private function methodViews() {
        $this->nameMethodViews = 'theme';

        if ($this->request->isAdminPanel()) {
            $this->nameMethodViews = 'admin';
        } elseif ($this->request->isLoginForm() || $this->request->isRegisterForm()) {
            $this->nameMethodViews = 'login';
        }
    }

    /**
     * Metodo que establece el nombre del modelo vista a incluir 
     * segun el metodo enviado por url.
     */
    private function getNameView() {
        switch ($this->request->getMethod()) {
            case 'delete':
            case 'index':
                $this->nameView = $this->request->getController();
                break;
            case 'insert':
            case 'update':
            case 'register':
                $this->nameView = $this->request->getController() . 'insert';
                break;
        }
    }

    /**
     * Metodo que obtiene los modulos vista del login y registro de usuario.
     * @param string $view Ruta de modulo vista a incluir.
     * @return array
     */
    private function login($view) {
        return [
            \VIEWS . 'headerlogin.php',
            $view,
            \VIEWS . 'footerlogin.php',
        ];
    }

    /**
     * Metodo que obtiene los modulos vista del tema de la aplicación.
     * @param string $view Ruta de modulo vista a incluir.
     * @return array
     */
    private function theme($view) {
        return [
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'header.php',
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'index.php',
            \THEMES . 'default' . \DIRECTORY_SEPARATOR . 'footer.php',
        ];
    }

    /**
     * Metodo que obtiene los modulos vista del panel de administración.
     * @param string $view Ruta de modulo vista a incluir.
     * @return array
     */
    private function admin($view) {
        return [
            \VIEWS . 'header.php',
            \VIEWS . 'topbar.php',
            \VIEWS . 'leftbar.php',
            $view,
            \VIEWS . 'footer.php',
        ];
    }

}
