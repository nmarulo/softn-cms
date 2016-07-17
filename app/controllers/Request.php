<?php

/**
 * Controlador de la URL.
 */

namespace SoftnCMS\controllers;

/**
 * Controlador de los datos de la URL.
 *
 * @author Nicolás Marulanda P.
 */
class Request {

    /** @var string Nombre del controlador. */
    private $controller;

    /** @var string Nombre de la carpeta. */
    private $folder;

    /** @var array Lista de argumentos. */
    private $args;

    public function __construct() {
        $this->folder = '';
        $this->controller = 'index.php';
        $this->args = [];
        $this->parseUrl();
    }

    /**
     * Metodo que comprueba el nombre de la carpeta.
     * @param string $folder [Opcional] Por defecto es constante ADMIN.
     * @return bool
     */
    public function isFolder($folder = \ADMIN) {
        return $this->folder == $folder;
    }

    /**
     * Metodo que obtiene el nombre del controlador.
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Metodo que obtiene el nombre de la carpeta.
     * @return string
     */
    public function getFolder() {
        return $this->folder;
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
            $this->selectFolder($url);
            $this->selectController($url);
            $this->selectArgs($url);
        }
    }

    /**
     * Metodo que establece la carpeta.
     * @param array $url
     */
    private function selectFolder($url) {
        /*
         * Solo existe la carpeta ADMIN,
         * ya que para ver la plantilla no hay que colocar nada.
         */
        if (!empty($url[0]) && $url[0] == \ADMIN) {
            $this->folder = \ADMIN;
        }
    }

    /**
     * Metodo que establece el nombre del controlador.
     * @param array $url
     */
    private function selectController($url) {
        
        if (!empty($url[1])) {
            $php = '.php';
            $this->controller = $url[1];
            
            if (\strpos($this->controller, $php) === \FALSE) {
                $this->controller .= $php;
            }
        }
    }

    /**
     * Metodo que establece los argumentos enviados.
     * @param array $url
     */
    private function selectArgs($url) {
        if (!empty($url[2])) {
            foreach ($url as $key => $arg) {
                if ($key > 1) {
                    $this->args[] = $arg;
                }
            }
        }
    }

}
