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

    /** @var bool Comprueba si esta accediendo al panel de administración. */
    private $isAdmin;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->isAdmin = false;
        $this->method = 'index';
        $this->controller = 'index';
        $this->args = [0];
        $this->parseUrl();
    }

    /**
     * Metodo que indica si esta en el panel de administración.
     * @return bool
     */
    public function isAdmin() {
        return $this->isAdmin;
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
        $get = filter_input(INPUT_GET, \URL_GET);

        if ($get) {
            $url = \rtrim($get, '/');
            $url = \filter_var($url, \FILTER_SANITIZE_URL);
            /*
             * [0] = carpeta
             * [1] = controlador
             * [>= 2] = argumentos
             */
            $url = \explode('/', $url);
            $url = $this->checkAdmin($url);
            $this->selectController(\array_shift($url));
            $this->selectMethod(\array_shift($url));
            $this->selectArgs($url);
        }
    }

    /**
     * Metodo que comprueba si esta en el panel de administración.
     * @param array $url
     * @return array
     */
    private function checkAdmin($url) {
        $aux = $url;
        $this->isAdmin = \ADMIN == \array_shift($aux);
        return $this->isAdmin ? $aux : $url;
    }

    /**
     * Metodo que establece el metodo a ejecutar.
     * @param array $url
     */
    private function selectMethod($url) {
        if (!empty($url)) {
            $this->method = $url;
        }
    }

    /**
     * Metodo que establece el nombre del controlador.
     * @param array $url
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
