<?php

/**
 * Controlador de la URL.
 */

namespace SoftnCMS\controllers;

/**
 * Clase que obtiene los datos enviatos por URL.
 *
 * @author Nicolás Marulanda P.
 */
class Request {

    /** @var string Nombre del controlador. */
    private $controller;

    /** @var string Nombre del metodo a ejecutar. */
    private $method;

    /** @var array Lista de argumentos. */
    private $args;

    /** @var bool Si es TRUE, el usuario esta en el panel de administración. */
    private $adminPanel;

    /** @var bool Si es TRUE, el usuario esta en el formulario de inicio de sesión. */
    private $loginForm;

    /** @var bool Si es TRUE, el usuario esta en el formulario de registro. */
    private $registerForm;

    /** @var bool Si es TRUE, el usuario solicito el cierre de sesión. */
    private $logout;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->adminPanel = false;
        $this->loginForm = false;
        $this->registerForm = false;
        $this->logout = false;
        $this->method = 'index';
        $this->controller = 'index';
        $this->args = [0];
        $this->parseUrl();
    }

    /**
     * Metod que indica si el usuario esta en el tema.
     * @return bool
     */
    public function isTheme() {
        return !$this->adminPanel && !$this->loginForm && !$this->registerForm && !$this->logout;
    }

    /**
     * Metodo que indica si el usuario esta en el panel de administración.
     * @return bool
     */
    public function isAdminPanel() {
        return $this->adminPanel;
    }

    /**
     * Metodo que indica si el usuario esta en el formulario de inicio de sesión.
     * @return bool
     */
    public function isLoginForm() {
        return $this->loginForm;
    }

    /**
     * Metodo que indica si el usuario esta en el formulario de registro.
     * @return bool
     */
    public function isRegisterForm() {
        return $this->registerForm;
    }

    /**
     * Metodo que indica si el usuario solicito el cierre de sesión.
     * @return bool
     */
    public function isLogout() {
        return $this->logout;
    }

    /**
     * Metodo que obtiene el nombre del controlador.
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Metodo que obtiene el nombre del metodo.
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Metodo que obtiene los argumentos.
     * @return array
     */
    public function getArgs() {
        return $this->args;
    }

    /**
     * Metodo que comprueba la url y obtiene sus datos.
     */
    private function parseUrl() {
        $get = \filter_input(\INPUT_GET, \URL_GET);

        if ($get) {
            $url = \rtrim($get, '/');
            $url = \filter_var($url, \FILTER_SANITIZE_URL);
            /*
             * La URL contiene:
             * [] = carpeta - en caso de estar en el panel de administración.
             * [] = controlador
             * [] = metodo
             * [>= ] = argumentos
             */
            $url = \explode('/', $url);
            $url = $this->checkUrl($url);
            $this->selectController(\array_shift($url));
            $this->selectMethod(\array_shift($url));
            $this->selectArgs($url);
        }
    }

    /**
     * Metodo que establece a TRUE o FALSE las variables booleanas 
     * que establecen la ubicación del usuario.
     * @param array $url Lista con los datos de la URL.
     * @return array Si el usuario esta en el panel de administración 
     * retornara la lista con los datos de la URL sin el primer dato 
     * ya que este solo sirve para identificar que el usuario 
     * esta en el panel de administración.
     */
    private function checkUrl($url) {
        $aux = $url;
        $value = \array_shift($aux);
        $this->adminPanel = \ADMIN == $value;
        $this->loginForm = 'login' == $value;
        $this->registerForm = 'register' == $value;
        $this->logout = 'logout' == $value;
        
        return $this->adminPanel ? $aux : $url;
    }

    /**
     * Metodo que establece el metodo a ejecutar.
     * @param string $url
     */
    private function selectMethod($url) {
        if (!empty($url)) {
            $this->method = $url;
        }
    }

    /**
     * Metodo que establece el nombre del controlador.
     * @param string $url
     */
    private function selectController($url) {
        if (!empty($url)) {
            $this->controller = $url;
        }
    }

    /**
     * Metodo que establece los argumentos enviados.
     * @param array $url
     */
    private function selectArgs($url) {
        if (!empty($url)) {
            $this->args = \is_array($url) ? $url : [$url];
        }
    }

}
